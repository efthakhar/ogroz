@extends('layouts.admin')
@section('page-content')
    <div class="d-flex flex-wrap align-items-center py-2">
        <h5 class="h4 me-auto">Account Details</h5>
        <div class="d-flex flex-wrap align-items-center">
            <a class="btn btn-primary m-1" href="{{ route('accounts.index') }}">
                <i class="ri-list-check"></i>
                View All
            </a>
        </div>
    </div>
    <div class="p-3 rounded bg-body border mt-2">
        <div class="row">
            <div class="col-md-4">
                <p class="fw-bold my-0">Name</p>
                <p class="text-secondary">{{ $account->name }}</p>
            </div>
            <div class="col-md-4">
                <p class="fw-bold my-0">Number</p>
                <p class="text-secondary">{{ $account->number }}</p>
            </div>
            <div class="col-md-4">
                <p class="fw-bold my-0">Under</p>
                <p class="text-secondary"> {{ $account->accountGroup?->name }} </p>
            </div>
        </div>
    </div>
@endsection
