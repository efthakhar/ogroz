@extends('layouts.admin')
@section('page-content')
    <div class="d-flex flex-wrap align-items-center py-2">
        <h5 class="h4 me-auto">{{ empty($accountGroup) ? 'Create' : 'Edit' }} Account Group</h5>
        <div class="d-flex flex-wrap align-items-center">
            <a class="btn btn-primary m-1" href="{{ route('account-groups.index') }}">
                <i class="ri-list-check"></i>
                View All
            </a>
        </div>
    </div>
    <div class="p-3 rounded bg-body border mt-2 table-responsive">
        {{ html()->form(!empty($accountGroup) ? 'PUT' : 'POST', !empty($accountGroup) ? route('account-groups.update', $accountGroup->id) : route('account-groups.store'))->open() }}
        <div class="user-create-form row">
            <span>{{ old('parent_account_group_id') }}</span>
            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Name', 'name')->addClass('form-label')->children([html()->span()->text('*')->class('text-danger')]),
                    html()->text('name')->addClass('form-control' . ($errors->has('name') ? ' is-invalid' : ''))->attribute('placeholder', 'Name')->attribute('required', true)->attribute('autocomplete', 'off')->value(old('name', $accountGroup->name ?? '')),
                    $errors->has('name') ? html()->span($errors->first('name'))->addClass('text-danger') : '',
                ]) }}


            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Type')->addClass('form-label'),
            
                    html()->select('type', $types)->addClass('form-select')->value(old('type') ?? ($accountGroup->type ?? '')),
            
                    $errors->has('type') ? html()->span($errors->first('type'))->addClass('text-danger d-block mt-2') : '',
                ]) }}

            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Under')->addClass('form-label'),
            
                    html()->select('parent_account_group_id', [])->addClass('form-select')->value(old('parent_account_group_id') ?? ($accountGroup->parent_account_group_id ?? '')),
            
                    $errors->has('parent_account_group_id')
                        ? html()->span($errors->first('parent_account_group_id'))->addClass('text-danger d-block mt-2')
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

            let grouptype = $('#type').val();
            let accountGroup = "{{ !empty($accountGroup) ? $accountGroup->id : '' }}";
            let selected =
            "{{ old('parent_account_group_id') ?? ($accountGroup->parent_account_group_id ?? '') }}";

            if (grouptype != '') {
                loadGroups(grouptype, selected, accountGroup);
            }

            $('#type').on('change', function() {
                loadGroups($(this).val(), '', accountGroup)
            })

            function loadGroups(grouptype = '', selected = '', omit) {
                // console.log(grouptype, selected)
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('account-groups.dropdown') }}",
                        dataType: "json",
                        data: {
                            'type': grouptype,
                            'omit': omit
                        },
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

                            $('#parent_account_group_id').empty()
                            $('#parent_account_group_id').select2({
                                data: formattedData,
                                placeholder: "=N/A=",
                                allowClear: true,
                            });

                            if (selected) {

                                if ($('#parent_account_group_id').find(
                                        `option[value="${selected}"]`).length) {
                                    $('#parent_account_group_id').val(selected).trigger(
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
