@extends('layouts.admin')

@section('page-content')
    <div class="d-flex flex-wrap align-items-center py-2">
        <h5 class="h4 me-auto">Profile Details</h5>
    </div>
    <div class="p-3 rounded bg-body border mt-2 table-responsive">
        {{ html()->form('POST', route('profile.submit'))->open() }}
        <div class="row">
            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Email', 'email')->addClass('form-label'),
                    html()->text('email')->addClass('form-control')->attribute('disabled', true)->value($user->email),
                ]) }}
            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Name', 'name')->addClass('form-label')->children([html()->span()->text('*')->class('text-danger')]),
                    html()->text('name')->addClass('form-control')->value(old('name') ?? ($user->name ?? '')),
                    $errors->has('name') ? html()->span($errors->first('name'))->addClass('text-danger') : '',
                ]) }}
        </div>
        <div class="row">
            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Old Password', 'old_password')->addClass('form-label'),
                    html()->password('old_password')->addClass('form-control')->attribute('autocomplete', 'off'),
                    $errors->has('old_password') ? html()->span($errors->first('old_password'))->addClass('text-danger') : '',
                ]) }}

            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('New Password', 'new_password')->addClass('form-label'),
                    html()->password('new_password')->addClass('form-control')->attribute('autocomplete', 'off'),
                    $errors->has('new_password') ? html()->span($errors->first('new_password'))->addClass('text-danger') : '',
                ]) }}
        </div>

        <div class="row">
            {{ html()->div()->addClass('col-md-12 my-2')->children([
                    html()->button()->type('submit')->addClass('btn btn-primary')->children([html()->i()->addClass('ri-checkbox-circle-line me-2'), html()->span()->text('Submit Data')]),
                ]) }}
        </div>
        {{ html()->form()->close() }}
    </div>
@endsection
