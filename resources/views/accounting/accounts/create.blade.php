@extends('layouts.admin')
@section('page-content')
    <div class="d-flex flex-wrap align-items-center py-2">
        <h5 class="h4 me-auto">{{ empty($account) ? 'Create' : 'Edit' }} Account</h5>
        <div class="d-flex flex-wrap align-items-center">
            <a class="btn btn-primary m-1" href="{{ route('accounts.index') }}">
                <i class="ri-list-check"></i>
                View All
            </a>
        </div>
    </div>
    <div class="p-3 rounded bg-body border mt-2 table-responsive">
        {{ html()->form(!empty($account) ? 'PUT' : 'POST', !empty($account) ? route('accounts.update', $account->id) : route('accounts.store'))->open() }}
        <div class="user-create-form row">

            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Name', 'name')->addClass('form-label')->children([html()->span()->text('*')->class('text-danger')]),
                    html()->text('name')->addClass('form-control' . ($errors->has('name') ? ' is-invalid' : ''))->attribute('placeholder', 'Name')->attribute('required', true)->attribute('autocomplete', 'off')->value(old('name', $account->name ?? '')),
                    $errors->has('name') ? html()->span($errors->first('name'))->addClass('text-danger') : '',
                ]) }}

            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Number', 'number')->addClass('form-label')->children([html()->span()->text('*')->class('text-danger')]),
            
                    html()->text('number')->addClass('form-control' . ($errors->has('number') ? ' is-invalid' : ''))->attribute('placeholder', 'Number')->attribute('required', true)->attribute('autocomplete', 'off')->value(old('number', $account->number ?? '')),
            
                    $errors->has('number') ? html()->span($errors->first('number'))->addClass('text-danger') : '',
                ]) }}


            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Under')->addClass('form-label'),
            
                    html()->select('account_group_id', [])
                    ->attribute('id', 'account_group_id')
                    ->addClass('form-select')
                    ->value(old('account_group_id') ?? ($account->account_group_id ?? '')),
            
                    $errors->has('account_group_id')
                        ? html()->span($errors->first('account_group_id'))->addClass('text-danger d-block mt-2')
                        : '',
                ]) }}

            <div class="row">
                {{ html()->div()->addClass('col-md-12 my-2')->children([
                        html()->button()->type('submit')->addClass('btn btn-primary')->children([html()->i()->addClass('ri-checkbox-circle-line me-2'), html()->span()->text('Submit Data')]),
                    ]) }}
            </div>
        </div>
        {{ html()->form()->close() }}
    </div>
@endsection

@section('page-scripts')
    <script>
        $(document).ready(function() {

            let selected = "{{ old('account_group_id') ?? ($account->account_group_id ?? '') }}";
            loadGroups(selected);

            function loadGroups(selected = '') {

                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('account-groups.dropdown') }}",
                        dataType: "json",
                        success: function(response) {
                            let nestedAccountGroups = buildNestedOptionsForSelect2(
                                response,
                                'parent_account_group_id',
                                'name',
                                'id',
                                null
                            );

                            const flattenData = (data, prefix = '') => data.flatMap(({
                                id,
                                text,
                                children
                            }) => [{
                                id,
                                text: prefix + text
                            }, ...flattenData(children, prefix + '- ')]);

                            const formattedData = flattenData(nestedAccountGroups);
                            
                            $('#account_group_id').empty()
                            $('#account_group_id').select2({
                                data: formattedData,
                                placeholder: "=N/A=",
                                allowClear: true,
                            });

                            if (selected) {

                                if ($('#account_group_id').find(
                                        `option[value="${selected}"]`).length) {
                                    $('#account_group_id').val(selected).trigger(
                                        'change');
                                } else {
                                    console.log(`Option with value ${selected} not found.`);
                                }
                            }


                            resolve(formattedData);
                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }

        });
    </script>
@endsection
