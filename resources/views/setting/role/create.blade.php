@extends('layouts.admin')
@section('page-content')
    <div class="d-flex flex-wrap align-items-center py-2">
        <h5 class="h4 me-auto">{{ empty($role) ? 'Create':'Edit'}} Role</h5>
        <div class="d-flex flex-wrap align-items-center">
            <a class="btn btn-primary m-1" href="{{ route('roles.index') }}">
                <i class="ri-list-check"></i>
                View All
            </a>
        </div>
    </div>
    <div class="p-3 rounded bg-body border mt-2 table-responsive">
        {{ html()->form(!empty($role) ? 'PUT' : 'POST', !empty($role) ? route('roles.update', $role->id) : route('roles.store'))->open() }}
        <div class="role-create-form row">

            {{ html()->div()->addClass('col-md-12 my-2')->children([
                    html()->label('Name', 'name')->addClass('form-label')->children([html()->span()->text('*')->class('text-danger')]),
                    html()->text('name')->addClass('form-control' . ($errors->has('name') ? ' is-invalid' : ''))->attribute('placeholder', 'Name')->attribute('required', true)->attribute('autocomplete', 'off')->value(old('name', $role->name ?? '')),
                    $errors->has('name') ? html()->span($errors->first('name'))->addClass('text-danger') : '',
                ]) }}

            <div class="col-md-12">
                @if ($errors->has('permissions'))
                    <p class="text-danger fw-light">{{ $errors->first('permissions') }}</p>
                @endif
                <table class="table table-bordered rounded overflow-hidden">
                    <thead>
                        <tr>
                            <th>Group</th>
                            <th>Permissions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissionGroups as $group => $permissions)
                            <tr>
                                <td>{{ $group }}</td>
                                <td>
                                    @foreach ($permissions as $permission)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $permission }}"
                                                id="{{ $permission }}" name='permissions[]'
                                                {{ !empty($role) ? (in_array($permission, $assignedPermissions) ? 'checked' : '') : '' }}>
                                            <label class="form-check-label cursor-pointer" for="{{ $permission }}">
                                                {{ $permission }}
                                            </label>
                                        </div>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                {{ html()->div()->addClass('col-md-12 my-2')->children([
                        html()->button()->type('submit')->addClass('btn btn-primary')->children([html()->i()->addClass('ri-checkbox-circle-line me-2'), html()->span()->text('Submit Data')]),
                    ]) }}
            </div>
        </div>
        {{ html()->form()->close() }}
    </div>
@endsection
