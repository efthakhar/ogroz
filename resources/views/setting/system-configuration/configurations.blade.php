@extends('layouts.admin')

@section('page-content')
    <div class="d-flex flex-wrap align-items-center py-2">
        <h5 class="h4 me-auto">System Configurations</h5>
    </div>
    <div class="p-3 rounded bg-body border mt-2 table-responsive">
        {{ html()->form('POST', route('system.configurations.submit'))->open() }}
        <div class="row">
            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Company Name', 'company_name')->addClass('form-label')->children([html()->span()->text('*')->class('text-danger')]),
                    html()->text('company_name')->addClass('form-control')->value(old('company_name') ?? ($systemConfigurations['company_name'] ?? '')),
                    $errors->has('company_name')
                        ? html()->span($errors->first('company_name'))->addClass('text-danger fw-light')
                        : '',
                ]) }}
            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Company Phone No', 'company_phone_no')->addClass('form-label')->children([html()->span()->text('*')->class('text-danger')]),
                    html()->text('company_phone_no')->addClass('form-control')->value(old('company_phone_no') ?? ($systemConfigurations['company_phone_no'] ?? '')),
                    $errors->has('company_phone_no')
                        ? html()->span($errors->first('company_phone_no'))->addClass('text-danger fw-light')
                        : '',
                ]) }}
            {{ html()->div()->addClass('col-md-4 my-2')->children([
                    html()->label('Company Email', 'company_email')->addClass('form-label')->children([html()->span()->text('*')->class('text-danger')]),
                    html()->text('company_email')->addClass('form-control')->value(old('company_email') ?? ($systemConfigurations['company_email'] ?? '')),
                    $errors->has('company_email')
                        ? html()->span($errors->first('company_email'))->addClass('text-danger fw-light')
                        : '',
                ]) }}
            {{ html()->div()->addClass('col-md-12 my-2')->children([
                    html()->label('Company Address', 'company_address')->addClass('form-label')->children([html()->span()->text('*')->class('text-danger')]),
                    html()->textarea('company_address')->addClass('form-control')->value(old('company_address') ?? ($systemConfigurations['company_address'] ?? '')),
                    $errors->has('company_address')
                        ? html()->span($errors->first('company_address'))->addClass('text-danger fw-light')
                        : '',
                ]) }}
        </div>
        <div class="row">
            {{ html()->div()->addClass('col-md-12 my-2')->children([
                    html()->button()->type('submit')->addClass('btn btn-primary')->children([
                            html()->i()->addClass('ri-checkbox-circle-line me-2'),
                            html()->span()->text('Submit Data'),
                        ]),
                ]) }}

        </div>

        {{ html()->form()->close() }}
    </div>
@endsection
