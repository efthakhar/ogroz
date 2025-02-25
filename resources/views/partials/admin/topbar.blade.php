<div
    class="topbar bg-body border-bottom px-4 d-flex align-items-center justify-content-between justify-content-md-start">
    <div class="sidebar-toggle-icon d-block d-sm-none me-5 cursor-pointer">
        <i class="ri-menu-2-fill ri-lg"></i>
    </div>
    <div class="user-select-none-full-area">
        <a href="{{ route('app') }}" class="cursor-pointer text-decoration-none h6">
            {{$globalData['system_configurations']['company_name'] ?? ''}}
        </a>
    </div>
    <div class="d-block ms-auto"></div>
    <div class="d-none d-sm-block me-2 cursor-pointer">
        Hi, {{ auth()->user()->name }}
    </div>
    <div class="user-select-none-full-area">
        <span class="cursor-pointer border rounded-2 px-2 py-1" data-bs-toggle="dropdown">
            <i class="ri-user-line ri-1x"></i>
        </span>
        <ul class="dropdown-menu">
            <li class="list-group-item d-block d-sm-none">
                <a class="dropdown-item btn">
                    Hi, {{ auth()->user()->name }}
                </a>
            </li>
            <li class="list-group-item">
                <a class="dropdown-item btn" href="{{ route('profile') }}">
                    <i class="ri-user-shared-line ri-1x ms-auto"></i>
                    <span class="ms-2">Profile</span>
                </a>
            </li>
            <li class="list-group-item theme-icon">
                <a class="dropdown-item btn">
                    <i class="ri-contrast-2-line ri-1x dark-theme-icon"></i>
                    <i class="ri-sun-line ri-1x light-theme-icon"></i>
                    <span class="ms-2">Theme</span>
                </a>
            </li>
            <li class="list-group-item">
                <a class="dropdown-item btn" href="{{ route('logout') }}">
                    <i class="ri-logout-circle-r-line ri-1x ms-auto"></i>
                    <span class="ms-2">Log Out</span>
                </a>
            </li>
        </ul>
    </div>
</div>
