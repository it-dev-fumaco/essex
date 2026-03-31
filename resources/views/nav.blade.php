<nav class="navbar navbar-expand-lg navbar-light w-100 portal-navbar">
    <div class="container-fluid portal-navbar-inner d-flex flex-wrap align-items-center px-2 px-lg-3">
        <a class="navbar-brand d-flex align-items-center gap-2 py-1 me-1 flex-shrink-0 min-w-0" href="{{ url('/') }}">
            <img src="{{ asset('storage/img/logo5.png') }}" alt="" class="portal-nav-essex-logo flex-shrink-0">
            <span class="d-none d-sm-inline-block text-start portal-nav-brand-stack min-w-0">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('upcloud')->url('logo/fumaco-transparent.png') }}" alt="Fumaco" class="portal-nav-fumaco-logo d-block mb-1">
                <span class="portal-nav-brand-sub">Employee Portal</span>
            </span>
        </a>
        <button class="navbar-toggler border-0 shadow-none px-2 ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse portal-navbar-collapse flex-grow-1 min-w-0" id="navbarSupportedContent">
            <div class="portal-navbar-toolbar d-flex flex-column flex-lg-row flex-lg-nowrap align-items-stretch align-items-lg-center justify-content-lg-between w-100 gap-2 gap-lg-3">
                <ul class="navbar-nav portal-navbar-main text-dark align-items-lg-center my-2 my-lg-0 flex-wrap flex-lg-nowrap">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}"><i class="icon-home"></i> Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="icon-info"></i> Updates
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ url('/gallery') }}"><i class="icon-hourglass"></i> Gallery</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('manuals*') ? 'active' : '' }}" href="{{ url('/manuals') }}"><i class="icon-notebook"></i> Manuals</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="icon-book-open"></i> Services
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ url('/services/internet') }}">Internet</a></li>
                            <li><a class="dropdown-item" href="{{ url('/services/email') }}">Email</a></li>
                            <li><a class="dropdown-item" href="{{ url('/services/system') }}">System</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="icon-briefcase"></i> Policy
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ url('/policies') }}">Operational Policies</a></li>
                            <li><a class="dropdown-item" href="{{ url('/itguidelines') }}">IT Guidelines and Policy</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('services/directory*') ? 'active' : '' }}" href="{{ url('/services/directory') }}"><i class="icon-briefcase"></i> Employee Directory</a>
                    </li>
                    @if(Auth::check())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('home') ? 'active' : '' }}" href="{{ url('/home') }}"><i class="icon-user"></i> My profile</a>
                        </li>
                    @endif
                </ul>
                <div class="portal-nav-account-outer d-flex align-items-center justify-content-stretch justify-content-lg-end flex-grow-1 min-w-0 w-100 pb-2 pb-lg-0">
                    <ul class="navbar-nav portal-nav-account w-100 w-lg-auto ms-lg-auto justify-content-lg-end">
                        @if(Auth::check())
                            <li class="nav-item dropdown account-setting">
                                <a href="#"
                                   class="account-menu-toggle dropdown-toggle"
                                   id="accountActionDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                    <span class="account-welcome">Welcome, {{ Auth::user()->employee_name }}!</span>
                                    <span class="account-gear-icon" aria-hidden="true">
                                        <i class="fas fa-cog"></i>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountActionDropdown">
                                    <li>
                                        <a class="dropdown-item header-change-password" href="/home" data-bs-toggle="modal" data-bs-target="#changePassword">
                                            <i class="fas fa-key me-2" aria-hidden="true"></i>Change Password
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/userLogout') }}">
                                            <i class="icon-logout me-2" aria-hidden="true"></i>Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item ms-lg-auto">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    <i class="icon-login"></i> <span>Login</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
