@extends('layouts.admin')
@section('page-head-scripts')
    <script src="https://unpkg.com/htmx.org@1.9.6"></script>
@endsection
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
        <div class="mt-2 table-responsive">
            <div id="journal-entries-table" hx-post="{{ route('journal-entries.datatable') }}" hx-trigger="load"
                hx-swap="outerHTML" hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'>
                Loading...
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')

@endsection
