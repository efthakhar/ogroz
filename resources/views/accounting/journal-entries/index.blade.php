@extends('layouts.admin')
@section('page-content')
    <div class="ogroz-data-table">
        <div class="d-flex flex-wrap align-items-center py-2">
            <h5 class="h4 me-auto">Journal Entries</h5>
            <div class="d-flex flex-wrap align-items-center">
                @can('delete accounts')
                    <button class="btn bulk-delete-rows text-danger shadow border bg-body m-1 d-none">
                        <i class="ri-delete-bin-7-line"></i>
                    </button>
                @endcan
                <button class="btn border bg-body text-body m-1 refresh-datatable">
                    <i class="ri-loop-left-line"></i>
                </button>
                <div class="btn-group ogroz-data-table__export">
                    <button class="btn border bg-body text-body m-1 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        data-bs-auto-close="outside" aria-expanded="false">
                        <i class="ri-download-line ri-1x"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item btn datatable-export" data-export-button-type='buttons-excel'>
                                Excel
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item btn datatable-export" data-export-button-type='buttons-csv'>
                                Csv
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item btn datatable-export" data-export-button-type='buttons-print'>
                                Print
                            </a>
                        </li>

                    </ul>
                </div>
                <div class="btn-group ogroz-data-table__column_filters">
                    <button class="btn border bg-body text-body m-1 dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <i class="ri-eye-line ri-1x"></i>
                    </button>
                    <ul class="dropdown-menu">
                    </ul>
                </div>

                @can('create journal entry')
                    <a class="btn btn-primary m-1" href="{{ route('journal-entries.create') }}">
                        <i class="ri-add-circle-line"></i>
                        Add New
                    </a>
                @endcan
            </div>
        </div>
        <div class="row mb-3" id="filters">
            <div class="col-md-3 my-1">
                <label for="" class="form-label">Date</label>
                <input type="date" class="form-control" name='filter_date' class="filter_date" id='filter_date'>
            </div>
            <div class="col-md-3 my-1">
                <label for="" class="form-label">Under</label>
                <select class="form-select" id="filter_account_group_id">
                    <option value="">=N/A=</option>
                </select>
            </div>
        </div>
        <div class="p-3 rounded bg-body border mt-2 table-responsive">
            <table class="table table-hover rounded-2 overflow-hidden display" id='accounts-table'>
            </table>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script>
        $(document).ready(function() {

            const columnDefinitions = [{
                    name: 'Select All',
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="select-row form-check-input" value="${row.id}" />`;
                    }
                },
                {
                    name: 'Date',
                    data: 'date',
                },
                {
                    name: 'Particulars',
                    data: 'particulars',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return data.map(
                            function(particular, index, array) {
                                return (row.credit[index]) ?
                                    `<p class='ps-4 my-0'>
                                    To, <a target='_blank' class='text-info text-decoration-none' href="{{ route('accounts.show', ':id') }}">${particular}</a></p>`
                                    .replace(':id', row.account_id[index]) :
                                    `<p class='my-0'>
                                        <a target='_blank' class='text-info text-decoration-none' href="{{ route('accounts.show', ':id') }}">${particular}</a></p>`
                                    .replace(':id', row.account_id[index]);
                            }
                        ).join('');
                    }
                },
                {
                    name: 'Debit',
                    data: 'debit',
                    orderable: false,
                    render: function(data, type, row, meta) {
                            return data.map(
                                function(debit, index, array) {
                                    return `<p class='text-right my-0'>${debit ?? '&nbsp' }</p>`;
                                }
                            ).join('');
                        }
                },
                {
                    name: 'Credit',
                    data: 'credit',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return data.map(
                            function(credit, index, array) {
                                return `<p class='text-right my-0'>${credit ?? '&nbsp'}</p>`;
                            }
                        ).join('');
                    }
                },

                @canany(['edit journal entry', 'delete journal entry', 'view journal entry'])
                    {
                        name: 'Action',
                        data: null,
                        orderable: false,
                        render: function(data, type, row, meta) {

                            let html = `<div class="dropdown position-static">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Action
                                            </button>
                                            <ul class="dropdown-menu">
                                            `;

                            @can('view account')
                                html += `<li>
                                            <a href="{{ route('journal-entries.show', ':id') }}" class="dropdown-item action-btn">
                                                <i class="ri-eye-line ri-1x me-2"></i> View
                                            </a>
                                        </li>`.replace(':id', row.id);
                            @endcan

                            @can('edit account')
                                html += `<li>
                                            <a href="{{ route('journal-entries.edit', ':id') }}" class="dropdown-item action-btn">
                                                <i class="ri-pencil-line ri-1x me-2"></i> Edit
                                            </a>
                                        </li>`.replace(':id', row.id);
                            @endcan

                            @can('delete account')
                                html += `<li>
                                            <a data-action-url="{{ route('journal-entries.destroy', ':id') }}" class="btn dropdown-item action-btn delete-item">
                                                 <i class="ri-delete-bin-line ri-1x me-2"></i> Delete
                                            </a>
                                        </li>`.replace(':id', row.id);
                            @endcan
                            html += `</ul></div>`;

                            return html;
                        }
                    }
                @endcanany

            ];

            const thead = $('<thead><tr></tr></thead>');
            const columnFilters = $('.ogroz-data-table__column_filters .dropdown-menu');

            columnDefinitions.forEach(column => {

                let th = '';

                if (column.name == 'Select All') {
                    th = $('<th></th>').html(
                        '<input type="checkbox" class="form-check-input select-all-rows" />');
                } else {
                    th = $('<th></th>').text(column.name)
                }
                thead.find('tr').append(th);

                columnFilters
                    .append(
                        `<li>
                            <a class="dropdown-item btn col-filter-item" data-col-name="${column.name}">
                                ${column.name}
                            </a>
                        </li>`
                    );
            });


            $('#accounts-table').empty().append(thead);

            const table = $('#accounts-table').DataTable({
                order: [],
                lengthMenu: [
                    [10, 25, 50, 100, 500, 1000],
                    [10, 25, 50, 100, 500, 1000]
                ],
                searching: false,
                processing: true,
                serverSide: true,
                responsive: true,
                stateSave: true,
                scrollCollapse: true,
                buttons: [{
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        },
                        filename: () => `account_groups_${getCurrentFormattedDate()}`,
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':visible'
                        },
                        filename: () => `account_groups_${getCurrentFormattedDate()}`,
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        },
                        title: "",
                    },
                ],

                ajax: {
                    url: '{{ route('journal-entries.datatable') }}',
                    type: 'POST',
                    data: function(d) {
                        d.date = $('#filter_date').val();
                        d.account_id = $('#filter_account_id').val();
                    }
                },
                columns: columnDefinitions.map(column => ({
                    data: column.data,
                    className: column.className || '',
                    orderable: column.orderable,
                    render: column.render
                })),


            });


            //=== Refresh Datatable
            $('.refresh-datatable').on('click', function(e) {
                table.draw();
                $(".bulk-delete-rows").addClass('d-none');
            })

            //== Refresh Datatable on Filter Change
            $('#filter_date, #filter_account_id').on('change', function(e) {
                table.draw();
            })

            //=== Trigger Exports PDF/CSV/EXCEL
            $('.datatable-export').off('click').on('click', function() {
                let buttonType = $(this).data('export-button-type');
                table.button(`.${buttonType}`).trigger();
            });

            //=== Handle columns visible state on click column
            $(document).on('click', '.col-filter-item', function(e) {

                let selectedColumnName = $(this).data('col-name');

                const columnIndex =
                    columnDefinitions
                    .findIndex(column => column.name == selectedColumnName);

                if (columnIndex !== -1) {
                    const column = table.column(columnIndex);
                    column.visible(!column.visible());
                    $(this).toggleClass('text-decoration-line-through');
                }
            })

            //=== Handle columns visible state on load page from prev state save
            table.columns().every(function(index) {
                var columnName = columnDefinitions[index].name;
                var isVisible = this.visible();
                if (isVisible == false) {
                    $(`[data-col-name="${columnName}"]`).addClass('text-decoration-line-through');
                }
            });

            //=== Handle Row Selection
            $(document).on('click', '.select-all-rows', function() {
                const isChecked = $(this).is(':checked');
                $(this).closest('.ogroz-data-table').find('.select-row').prop('checked', isChecked);
            });

            $(document).on('click', '.select-row, .select-all-rows', function() {
                const totalCheckboxes = $(this).closest('.ogroz-data-table').find('.select-row').length;
                const checkedCheckboxes = $(this).closest('.ogroz-data-table').find('.select-row:checked')
                    .length;
                $('.select-all-rows').prop('checked', totalCheckboxes === checkedCheckboxes);

                parseInt(checkedCheckboxes) > 0 ?
                    $(this).closest('.ogroz-data-table').find('.bulk-delete-rows').removeClass('d-none') :
                    $(this).closest('.ogroz-data-table').find('.bulk-delete-rows').addClass('d-none');
            });


            //=== Handle Item Delete
            $(document).on('click', '.ogroz-data-table .delete-item', function(e) {

                e.preventDefault();
                const element = $(this);
                const url = element.data('action-url');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        type: "DELETE",
                        url,
                        success: ({
                            message
                        }) => {
                            element.closest('tr').hide();
                            if (message) {
                                Swal.fire({
                                    toast: true,
                                    position: "top-end",
                                    icon: "success",
                                    title: message,
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR.responseJSON?.message)
                            if (jqXHR.responseJSON?.message) {
                                Swal.fire({
                                    toast: true,
                                    position: "top-end",
                                    icon: "error",
                                    title: jqXHR.responseJSON?.message,
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                            }
                        }
                    });
                });
            });

            //=== Handle Bulk Delete
            $('.bulk-delete-rows').on('click', function(e) {

                const selectedIds = [];
                $('.select-row:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                e.preventDefault();
                const element = $(this);
                const url = "{{ route('journal-entries.destroy', ':id') }}".replace(':id', selectedIds);

                Swal.fire({

                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",

                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.ajax({
                        type: "DELETE",
                        url,
                        success: ({
                            message
                        }) => {
                            element.closest('tr').hide();
                            if (message) {
                                Swal.fire({
                                    toast: true,
                                    position: "top-end",
                                    icon: "success",
                                    title: message,
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                            }
                            $(".bulk-delete-rows").addClass('d-none');
                            $('.select-row:checked').closest('tr').remove();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR.responseJSON?.message)
                            if (jqXHR.responseJSON?.message) {
                                Swal.fire({
                                    toast: true,
                                    position: "top-end",
                                    icon: "error",
                                    title: jqXHR.responseJSON?.message,
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                            }
                        }
                    });
                });
            });

        });
    </script>
@endsection
