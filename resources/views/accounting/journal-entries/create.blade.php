@extends('layouts.admin')
@section('page-content')
    <style>
        .journal-entry-lines-table td input,
        .journal-entry-lines-table td select,
        .select2-selection__rendered {
            border: none !important;
        }

        .journal-entry-lines-table input,
        .journal-entry-lines-table select {
            border: none !important;
            outline: none !important;
            padding: 5px;
            width: 100%;
        }

        .journal-entry-lines-table .select2-container--default .select2-selection--single {
            border: none !important;
        }

        .journal-entry-lines-table .select2-container {
            width: 100% !important;
            min-width: 250px;
            display: block;
        }

        .select2-container .select2-selection--single,
        .select2-container .select2-selection--multiple {
            width: 100%;
            box-sizing: border-box;
        }

        .journal-entry-lines-table .select2-container .select2-selection--single .select2-selection__rendered,
        .journal-entry-lines-table .select2-container .select2-selection--multiple .select2-selection__rendered {
            width: 100%;
            box-sizing: border-box;
        }

        .journal-entry-lines-table .select2-container .select2-search--dropdown .select2-search__field {
            width: 100%;
            box-sizing: border-box;
        }

        .journal-entry-lines-table td {
            padding: 0;
            margin: 0;
            vertical-align: middle !important;
        }

        .debit,
        .credit {
            text-align: right;
        }
    </style>
    <div class="d-flex flex-wrap align-items-center py-2">
        <h5 class="h4 me-auto">{{ empty($journalEntry) ? 'Create' : 'Edit' }} Journal Entry</h5>
        <div class="d-flex flex-wrap align-items-center">
            <a class="btn btn-primary m-1" href="{{ route('journal-entries.index') }}">
                <i class="ri-list-check"></i>
                View All
            </a>
        </div>
    </div>

    <div class="mt-2">
        {{ html()->form(!empty($journalEntry) ? 'PUT' : 'POST', !empty($journalEntry) ? route('journal-entries.update', $journalEntry->id) : route('journal-entries.store'))->open() }}
        <div class="row">

            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Date', 'date')->addClass('form-label')->children([html()->span()->text('*')->class('text-danger')]),
            
                    html()->date('date')->addClass('form-control' . ($errors->has('date') ? ' is-invalid' : ''))->attribute('required', true)->value(old('date', $journalEntry->date ?? '')),
            
                    $errors->has('date') ? html()->span($errors->first('date'))->addClass('text-danger') : '',
                ]) }}


            <div class="col-12 table-responsive px-2">
                <table class="mt-5  mb-0 table table-bordered journal-entry-lines-table">
                    <thead>
                        <tr>
                            <td colspan="4" class="text-center fw-bold h4 py-2">Particulars</td>
                        </tr>
                        <tr>
                            <th>Account</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty(old('journalEntryLines')))
                            @foreach (old('journalEntryLines') as $index => $journalEntryLine)
                                <tr>
                                    <td>
                                        <select name="journalEntryLines[':sl'][account_id]"
                                            class="account_id form-select line_field">
                                            <option value="{{ $journalEntryLine['account_id'] }}">
                                                {{ old('selected_accounts')[$journalEntryLine['account_id']] ?? 'Select an Account' }}

                                            </option>
                                        </select>
                                        @error("journalEntryLines.{$index}")
                                            <span class="text-danger m-1 text-sm">{{ $message }}</span>
                                        @enderror
                                        @error("journalEntryLines.{$index}.account_id")
                                            <span class="text-danger m-1 text-sm">{{ $message }}</span>
                                        @enderror

                                    </td>
                                    <td>
                                        <input type="text" name="journalEntryLines[':sl'][debit]"
                                            class="debit line_field" value="{{ $journalEntryLine['debit'] ?? 0 }}">
                                        @error("journalEntryLines.{$index}.debit")
                                            <span class="text-danger m-1 text-sm text-right">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" name="journalEntryLines[':sl'][credit]"
                                            class="credit line_field" value="{{ $journalEntryLine['credit'] ?? 0 }}">
                                        @error("journalEntryLines.{$index}.credit")
                                            <span class="text-danger m-1 text-right">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td class="text-center p-1">
                                        <span class="btn-sm btn  text-danger border m-1 px-2 py-1 remove-line">
                                            <i class="ri-subtract-line ri-xl"></i>
                                        </span>
                                    </td>

                                </tr>
                            @endforeach
                        @elseif(!empty($journalEntry->journalEntryLines))
                            @foreach ($journalEntry->journalEntryLines as $journalEntryLine)
                                <tr>
                                    <td>
                                        <select name="journalEntryLines[':sl'][account_id]"
                                            class="account_id form-select line_field">
                                            <option value="{{ $journalEntryLine['account_id'] }}">
                                                {{ $selected_accounts[$journalEntryLine['account_id']] ?? 'Select an Account' }}
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="journalEntryLines[':sl'][debit]"
                                            class="debit line_field" value="{{ $journalEntryLine['debit'] ?? 0 }}">
                                    </td>
                                    <td>
                                        <input type="text" name="journalEntryLines[':sl'][credit]"
                                            class="credit line_field" value="{{ $journalEntryLine['credit'] ?? 0 }}">
                                    </td>
                                    <td class="text-center p-1">
                                        <span class="btn-sm btn  text-danger border m-1 px-2 py-1 remove-line">
                                            <i class="ri-subtract-line ri-xl"></i>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot class="border">
                        <tr>
                            <td colspan="4">
                                <span class="d-block bg-success-subtle text-center py-1 cursor-pointer add_line">
                                    Add New Line <i class="ri-add-line ri-xl ms-2"></i>
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{ html()->div()->addClass('col-md-12 my-5')->children([
                    html()->label('Description', 'description')->addClass('form-label'),
            
                    html()->textarea('description')->addClass('form-control')->attribute('rows', 2)->value(old('description') ?? ($journalEntry->description ?? '')),
            
                    $errors->has('description')
                        ? html()->span($errors->first('description'))->addClass('text-danger fw-light')
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

            // on page load initialize select2 on every account field and reindex the rows
            $('.journal-entry-lines-table tbody tr .account_id').each(function(index, accountField) {
                let $this = $(this);
                initializeSelect2($this);
            });
            reindexJournalLines();



            $('.journal-entry-lines-table .add_line').on('click', function(e) {

                let journalEntryLines = $('.journal-entry-lines-table tbody');
                let newLine =
                    `<tr>
                        <td>
                            <select name="journalEntryLines[':sl'][account_id]" class="account_id form-select line_field">
                                <option value="">Select an Account</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="journalEntryLines[':sl'][debit]" class="debit line_field">
                        </td>
                        <td>
                            <input type="text" name="journalEntryLines[':sl'][credit]" class="credit line_field">
                        </td>
                        <td class="text-center p-1">
                            <span class="btn-sm btn  text-danger border m-1 px-2 py-1 remove-line">
                                <i class="ri-subtract-line ri-xl"></i>
                            </span>
                        </td>
                    </tr>`;
                journalEntryLines.append(newLine);
                initializeSelect2(journalEntryLines.find('tr:last-child .account_id'));
                reindexJournalLines();
            })



            $('.journal-entry-lines-table').on('click', '.remove-line', function() {
                $(this).closest('tr').remove();
                reindexJournalLines();
            });

            $('.journal-entry-lines-table').on('input', '.debit', function() {
                $(this).closest('tr').find('.credit').val('')
            });

            $('.journal-entry-lines-table').on('input', '.credit', function() {
                $(this).closest('tr').find('.debit').val('')
            });


            function initializeSelect2(field) {
                field.select2({
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
            }

            function reindexJournalLines() {
                $('.journal-entry-lines-table tbody tr').each(function(sl, row) {
                    let $this = $(this);

                    $this.find('.line_field').each(function(index, element) {
                        let $element = $(element);
                        $element.attr('name', ($element.attr('name').replace("[':sl']",
                            `[${sl}]`)));
                    });
                });
            }
        });
    </script>
@endsection
