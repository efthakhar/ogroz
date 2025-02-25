@extends('layouts.admin')
@section('page-content')
    <div class="d-flex flex-wrap align-items-center py-2">
        <h5 class="h4 me-auto">User Details</h5>
        <div class="d-flex flex-wrap align-items-center">
            <a class="btn btn-primary m-1" href="{{ route('users.index') }}">
                <i class="ri-list-check"></i>
                View All
            </a>
        </div>
    </div>
    <div class="p-3 rounded bg-body border mt-2">
        <div class="row">
            <div class="col-md-4">
                <p class="fw-bold my-0">Name</p>
                <p class="text-secondary">{{ $user->name }}</p>
            </div>
            <div class="col-md-4">
                <p class="fw-bold my-0">Email</p>
                <p class="text-secondary">{{ $user->email }}</p>
            </div>
            <div class="col-md-4">
                <p class="fw-bold my-0">Active Status</p>
                <p class="{{ $user->active ? 'text-success' : 'text-muted' }}">
                    {{ $user->active == 1 ? 'Active' : 'Inactive' }}
                </p>
            </div>
            <div class="col-md-4">
                <p class="fw-bold my-0">Roles</p>
                <p class="text-secondary"> {{ $user->roles->pluck('name')->implode(', ') }} </p>
            </div>
        </div>
    </div>
@endsection
