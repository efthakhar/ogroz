<div class="sidebar bg-body border-end d-flex flex-column justify-content-between">
    <div class="sidebar-nav px-2 py-2">
        <div class="sidebar-nav-item">
            <a href="{{ route('app') }}"
                class="sidebar-nav-link text-body {{ request()->routeIs('app') ? 'active' : '' }}">
                <span class="sidebar-nav-link-icon">
                    <i class="ri-home-9-line ri-lg"></i>
                </span>
                <span class="sidebar-nav-link-text">
                    App
                </span>
            </a>
        </div>
        <div class="sidebar-nav-item">
            <a class="sidebar-nav-link text-body ">
                <span class="sidebar-nav-link-icon">
                    <i class="ri-settings-2-line ri-lg"></i>
                </span>
                <span class="sidebar-nav-link-text">
                    Setting
                </span>
                <span class="sidebar-nav-link-toggle-icon ms-auto">
                    <i class="ri-arrow-down-s-fill"></i>
                </span>
            </a>
            <div
                class="sidebar-nav-item-subitems {{ request()->routeIs('profile', 'users.*', 'system.configurations', 'roles.*') ? 'collapsed' : '' }}">
                <div class="sidebar-nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <a class="sidebar-nav-link text-body" href="{{ route('profile') }}">
                        <span class="sidebar-nav-link-icon">
                            <i class="ri-user-line ri-lg"></i>
                        </span>
                        <span class="sidebar-nav-link-text">
                            Profile
                        </span>
                    </a>
                </div>
                @can('view user')
                    <div class="sidebar-nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a class="sidebar-nav-link text-body" href="{{ route('users.index') }}">
                            <span class="sidebar-nav-link-icon">
                                <i class="ri-group-line ri-lg"></i>
                            </span>
                            <span class="sidebar-nav-link-text">
                                Users
                            </span>
                        </a>
                    </div>
                @endcan
                @can('view role')
                    <div class="sidebar-nav-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <a class="sidebar-nav-link text-body" href="{{ route('roles.index') }}">
                            <span class="sidebar-nav-link-icon">
                                <i class="ri-user-forbid-line ri-lg"></i>
                            </span>
                            <span class="sidebar-nav-link-text">
                                Roles
                            </span>
                        </a>
                    </div>
                @endcan
                @can('manage system configurations')
                    <div class="sidebar-nav-item {{ request()->routeIs('system.configurations') ? 'active' : '' }}">
                        <a class="sidebar-nav-link text-body" href="{{ route('system.configurations') }}">
                            <span class="sidebar-nav-link-icon">
                                <i class="ri-tools-fill ri-lg"></i>
                            </span>
                            <span class="sidebar-nav-link-text">
                                System
                            </span>
                        </a>
                    </div>
                @endcan
            </div>
        </div>
        <div class="sidebar-nav-item">
            <a class="sidebar-nav-link text-body">
                <span class="sidebar-nav-link-icon">
                    <i class="ri-discount-percent-line ri-lg"></i>
                </span>
                <span class="sidebar-nav-link-text">
                    Accounting
                </span>
                <span class="sidebar-nav-link-toggle-icon ms-auto">
                    <i class="ri-arrow-down-s-fill"></i>
                </span>
            </a>
            <div
                class="sidebar-nav-item-subitems {{ request()->routeIs('account-groups.*', 'accounts.*', 'journal-entries.*') ? 'collapsed' : '' }}">
                <div class="sidebar-nav-item ">
                    <a class="sidebar-nav-link text-body">
                        <span class="sidebar-nav-link-icon">
                            <i class="ri-dashboard-3-line ri-lg"></i>
                        </span>
                        <span class="sidebar-nav-link-text">
                            Dashboard
                        </span>
                    </a>
                </div>
                <div class="sidebar-nav-item">
                    <a class="sidebar-nav-link text-body {{ request()->routeIs('account-groups.*') ? 'active' : '' }}"
                        href="{{ route('account-groups.index') }}">
                        <span class="sidebar-nav-link-icon">
                            <i class="ri-book-shelf-line ri-lg"></i>
                        </span>
                        <span class="sidebar-nav-link-text">
                            Account Groups
                        </span>
                    </a>
                </div>
                <div class="sidebar-nav-item">
                    <a class="sidebar-nav-link text-body {{ request()->routeIs('accounts.*') ? 'active' : '' }}"
                        href="{{ route('accounts.index') }}">
                        <span class="sidebar-nav-link-icon">
                            <i class="ri-article-line ri-lg"></i>
                        </span>
                        <span class="sidebar-nav-link-text">
                            Accounts
                        </span>
                    </a>
                </div>
                <div class="sidebar-nav-item ">
                    <a class="sidebar-nav-link text-body {{ request()->routeIs('journal-entries.*') ? 'active' : '' }}"
                        href="{{ route('journal-entries.index') }}">
                        <span class="sidebar-nav-link-icon">
                            <i class="ri-book-open-line ri-lg"></i>
                        </span>
                        <span class="sidebar-nav-link-text">
                            Journal Entries
                        </span>
                    </a>
                </div>
                <div class="sidebar-nav-item ">
                    <a class="sidebar-nav-link text-body">
                        <span class="sidebar-nav-link-icon">
                            <i class="ri-pie-chart-2-line ri-lg"></i>
                        </span>
                        <span class="sidebar-nav-link-text">
                            Reports
                        </span>
                        <span class="sidebar-nav-link-toggle-icon ms-auto">
                            <i class="ri-arrow-down-s-fill"></i>
                        </span>
                    </a>
                    <div class="sidebar-nav-item-subitems">
                        <div class="sidebar-nav-item ">
                            <a class="sidebar-nav-link text-body">
                                <span class="sidebar-nav-link-icon">
                                    <i class="ri-git-repository-line ri-lg"></i>
                                </span>
                                <span class="sidebar-nav-link-text">
                                    Ledger Report
                                </span>
                            </a>
                        </div>
                        <div class="sidebar-nav-item ">
                            <a class="sidebar-nav-link text-body">
                                <span class="sidebar-nav-link-icon">
                                    <i class="ri-wallet-3-line ri-lg"></i>
                                </span>
                                <span class="sidebar-nav-link-text">
                                    Trial Balance
                                </span>
                            </a>
                        </div>
                        <div class="sidebar-nav-item ">
                            <a class="sidebar-nav-link text-body">
                                <span class="sidebar-nav-link-icon">
                                    <i class="ri-coins-line ri-lg"></i>
                                </span>
                                <span class="sidebar-nav-link-text">
                                    Income Statement
                                </span>
                            </a>
                        </div>
                        <div class="sidebar-nav-item ">
                            <a class="sidebar-nav-link text-body">
                                <span class="sidebar-nav-link-icon">
                                    <i class="ri-scales-line ri-lg"></i>
                                </span>
                                <span class="sidebar-nav-link-text">
                                    Balance Sheet
                                </span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div
        class="bg-body width-100 cursor-pointer rounded-0 sidebar-toggle-icon d-flex justify-content-center border-top p-2 position-sticky bottom-0 margin-top-auto ">
        <i class="ri-arrow-left-double-line ri-lg"></i>
    </div>
</div>
