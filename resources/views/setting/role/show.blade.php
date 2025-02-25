@extends('layouts.admin')
@section('page-content')
    <div class="d-flex flex-wrap align-items-center py-2">
        <h5 class="h4 me-auto">Role Details</h5>
        <div class="d-flex flex-wrap align-items-center">
            <a class="btn btn-primary m-1" href="{{ route('roles.index') }}">
                <i class="ri-list-check"></i>
                View All
            </a>
        </div>
    </div>
    <div class="p-3 rounded bg-body border mt-2">
        <div class="row">
            <div class="col-md-4">
                <p class="fw-bold my-0">Name</p>
                <p class="text-secondary">{{ $role->name }}</p>
            </div>
            <div class="col-md-12">
                @foreach($role->permissions as $permission)
                    <span class="badge bg-light text-dark p-3 m-1 h5">{{$permission->name}}</span>
                @endforeach
            </div>
        </div>
    </div>
@endsection
