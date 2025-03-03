@extends('layouts.admin')
@section('page-content')
    <div class="d-flex flex-wrap align-items-center py-2">
        <h5 class="h4 me-auto">{{ empty($journalEntry) ? 'Create' : 'Edit' }} Journal Entry</h5>
        <div class="d-flex flex-wrap align-items-center">
            <a class="btn btn-primary m-1" href="{{ route('journal-entries.index') }}">
                <i class="ri-list-check"></i>
                View All
            </a>
        </div>
    </div>
    <style>
        /* .journal-entry-lines td {
            padding: 0;
            
        } */
        /* .journal-entry-lines td input,  .journal-entry-lines td select, .select2-selection__rendered{
           border: none !important;
            
        } */

        /* .select2-container--default .select2-selection--single {
            border: none !important;
        } */
    </style>
    <div class="p-3 rounded bg-body border mt-2">
        {{ html()->form(!empty($journalEntry) ? 'PUT' : 'POST', !empty($journalEntry) ? route('journal-entries.update', $journalEntry->id) : route('journal-entries.store'))->open() }}
        <div class="row">

            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Date', 'date')->addClass('form-label')->children([html()->span()->text('*')->class('text-danger')]),
            
                    html()->date('date')->addClass('form-control' . ($errors->has('date') ? ' is-invalid' : ''))->attribute('required', true)->value(old('date', $journalEntry->date ?? '')),
            
                    $errors->has('date') ? html()->span($errors->first('date'))->addClass('text-danger') : '',
                ]) }}

            {{ html()->div()->addClass('col-md-8 my-2')->children([
                    html()->label('Description', 'description')->addClass('form-label'),
            
                    html()->textarea('description')->addClass('form-control')->attribute('rows', 1)->value(old('description') ?? ($journalEntry->description ?? '')),
            
                    $errors->has('description')
                        ? html()->span($errors->first('description'))->addClass('text-danger fw-light')
                        : '',
                ]) }}

            {{-- Journal Entry Lines  Start --}}
 
                <table class="my-5 table table-borderless journal-entry-lines">
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="accountJournalEntries[]['account_id']" class="account_id form-select">
                                    <option value="">Select an Account</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="accountJournalEntries[]['debit']" class="form-control debit">
                            </td>
                            <td>
                                <input type="number" name="accountJournalEntries[]['credit']" class="form-control credit">
                            </td>
                            <td class="text-center">
                                <i class="ri-delete-bin-line ri-xl text-danger btn"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <select name="accountJournalEntries[]['account_id']" class="account_id form-select">
                                    <option value="">Select an Account</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="accountJournalEntries[]['debit']" class="form-control debit">
                            </td>
                            <td>
                                <input type="number" name="accountJournalEntries[]['credit']" class="form-control credit">
                            </td>
                            <td class="text-center">
                                <i class="ri-delete-bin-line ri-xl text-danger btn"></i>
                            </td>
                        </tr>

                    </tbody>
                </table>
           
            {{-- Journal Entry Lines End --}}

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

            $('.account_id').select2({
                ajax: {
                    url: "{{ route('accounts.dropdown') }}",
                    dataType: 'json',
                    delay: 30,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: $.map(data.data, function(
                            item) { 
                                return {
                                    id: item.id,
                                    text: item.name 
                                };
                            }),
                            pagination: {
                                more: (params.page * 5) < data.total 
                            }
                        };
                    },
                    cache: true
                },
            });

        });
    </script>
@endsection
