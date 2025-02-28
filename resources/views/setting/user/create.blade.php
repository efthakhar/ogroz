@extends('layouts.admin')
@section('page-content')
    <div class="d-flex flex-wrap align-items-center py-2">
        <h5 class="h4 me-auto"> {{ empty($user) ? 'Create':'Edit'}} User</h5>
        <div class="d-flex flex-wrap align-items-center">
            <a class="btn btn-primary m-1" href="{{ route('users.index') }}">
                <i class="ri-list-check"></i>
                View All
            </a>
        </div>
    </div>
    <div class="p-3 rounded bg-body border mt-2 table-responsive">
        {{ html()->form(!empty($user) ? 'PUT' : 'POST', !empty($user) ? route('users.update', $user->id) : route('users.store'))->open() }}
        <div class="user-create-form row">

            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Name', 'name')->addClass('form-label')->children([html()->span()->text('*')->class('text-danger')]),
                    html()->text('name')->addClass('form-control' . ($errors->has('name') ? ' is-invalid' : ''))->attribute('placeholder', 'Name')->attribute('required', true)->attribute('autocomplete', 'off')->value(old('name', $user->name ?? '')),
                    $errors->has('name') ? html()->span($errors->first('name'))->addClass('text-danger') : '',
                ]) }}

            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Email', 'email')->addClass('form-label')->children([html()->span()->text('*')->class('text-danger')]),
                    html()->email('email')->addClass('form-control' . ($errors->has('email') ? ' is-invalid' : ''))->attribute('placeholder', 'Email')->attribute('required', true)->attribute('autocomplete', 'off')->value(old('email', $user->email ?? '')),
                    $errors->has('email') ? html()->span($errors->first('email'))->addClass('text-danger') : '',
                ]) }}

            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Active Status')->addClass('form-label')->children([html()->span()->text('*')->class('text-danger')]),
            
                    html()->select('active', [
                            1 => 'Active',
                            0 => 'Inactive',
                        ])->addClass('form-select')->value(old('active') ?? ($user->active ?? '')),
            
                    $errors->has('active') ? html()->span($errors->first('active'))->addClass('text-danger d-block mt-2') : '',
                ]) }}

            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Roles')->addClass('form-label'),
            
                    html()->select('roles[]', $roles ?? [])->attribute('multiple', true)->addClass('form-select roles')->value(old('roles', !empty($user) ? $user->roles->pluck('id')->toArray() : []))->id('roles'),
            
                    $errors->has('roles') ? html()->span($errors->first('roles'))->addClass('text-danger d-block mt-2') : '',
                ]) }}


            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Password', 'password')->addClass('form-label')->children([!empty($user) ? '' : html()->span()->text('*')->class('text-danger')]),
            
                    html()->password('password')->addClass('form-control' . ($errors->has('password') ? ' is-invalid' : ''))->attribute('placeholder', 'Password')
                    ->when(empty($user), fn($input) => $input->attribute('required'))
                    ->attribute('autocomplete', 'off'),
            
                    $errors->has('password') ? html()->span($errors->first('password'))->addClass('text-danger') : '',
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

            $('.roles').select2();

        });
    </script>
@endsection
