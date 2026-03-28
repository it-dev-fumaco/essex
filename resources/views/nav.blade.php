<div class="row">
    <div class="col-12">
        <nav class="navbar navbar-expand-xl navbar-light w-100">
            <div class="container-fluid align-items-center px-2 px-xl-3">
                <a class="navbar-brand d-flex align-items-center gap-2 py-1 me-2" href="{{ url('/') }}">
                    <img src="{{ asset('storage/img/logo5.png') }}" alt="" style="max-height: 44px; width: auto;">
                    <span class="d-none d-sm-inline-block text-start">
                        <span class="portal-nav-brand-text d-block">Essex Fumaco</span>
                        <span class="portal-nav-brand-sub">Employee Portal</span>
                    </span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-xl-auto my-2 my-xl-0 text-dark align-items-xl-center">
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
                </div>
            </div>
        </nav>
    </div>
</div>
<style>
    .nav-item{
        margin: 0 4px;
    }
</style>
