@extends('layouts.admin')
@section('page-content')
    <div class="border my-2 bg-body rounded  px-3 py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 px-2 bg-body text-left h-100">
                    <div class="card-body text-left">
                        <h6 class="p my-0 text-secondary">Welcome to</h6>
                        <h3 class="h3 mb-1 text-body">Ogroz Accounting Software</h3>
                        <h6 class="text-secondary p">Developed & Maintained By
                            <a class="fst-italic" target="_blank" href="https://github.com/efthakhar">Efthakhar Bin Alam
                                Dihab</a>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row flex-wrap">
            <div class="col-md-4 my-2">
                <div class="card border-0 p-2 bg-body text-left h-100">
                    <div class="card-body">
                        <p class="h6 text-body">
                            Setting
                        </p>
                        <a href="{{ route('profile') }}" class="link-secondary me-2 mb-1">
                            <span>Profile</span>
                            <i class="ri-arrow-right-up-fill"></i>
                        </a>
                        <a href="{{ route('users.index') }}" class="link-secondary mb-1">
                            <span>Users</span>
                            <i class="ri-arrow-right-up-fill"></i>
                        </a>
                        <br>
                        <a href="{{ route('system.configurations') }}" class="link-secondary me-2 mb-1">
                            <span>System</span>
                            <i class="ri-arrow-right-up-fill"></i>
                        </a>
                        <a href="{{ route('roles.index') }}" class="link-secondary mb-1">
                            <span>Roles</span>
                            <i class="ri-arrow-right-up-fill"></i>
                        </a>
                       
                        <a href="{{ route('logout') }}" class="link-secondary d-block mb-1">
                            <span>Logout</span>
                            <i class="ri-arrow-right-up-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="card border-0 p-2 bg-body text-left">
                    <div class="card-body">
                        <p class="h6 text-body">
                            Accounting
                        </p>
                        <a href="{{ route('account-groups.index') }}" class="link-secondary d-block mb-1">
                            <span> Account Groups</span>
                            <i class="ri-arrow-right-up-fill"></i>
                        </a>
                        <a href="{{ route('accounts.index') }}" class="link-secondary d-block mb-1">
                            <span>Accounts</span>
                            <i class="ri-arrow-right-up-fill"></i>
                        </a>
                        <a href="{{ route('journal-entries.index') }}" class="link-secondary d-block mb-1">
                            <span>Journal Entries</span>
                            <i class="ri-arrow-right-up-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-4 my-2">
                <div class="card border-0 p-2 bg-body text-left">
                    <div class="card-body">
                        <p class="h6 text-body">
                            Reports
                        </p>
                        <a href="#" class="link-secondary d-block mb-1">
                            <span>Ledger Report</span>
                            <i class="ri-arrow-right-up-fill"></i>
                        </a>
                        <a href="#" class="link-secondary d-block mb-1">
                            <span> Trial Balance</span>
                            <i class="ri-arrow-right-up-fill"></i>
                        </a>

                        <a href="#" class="link-secondary d-block mb-1">
                            <span>Income Statement</span>
                            <i class="ri-arrow-right-up-fill"></i>
                        </a>
                        <a href="#" class="link-secondary d-block mb-1">
                            <span>Balance Sheet</span>
                            <i class="ri-arrow-right-up-fill"></i>
                        </a>

                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection
