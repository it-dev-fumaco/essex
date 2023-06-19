<div class="row">
    <div class="col-lg-12 col-xl-{{ Auth::check() && Auth::user()->department_id == 9 ? 8 : 7 }} d-flex justify-content-start justify-content-xl-center align-items-center">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <div class="col-2 col-xl-3">
                    <div class="row" style="display: flex; justify-content: center; align-items: center;">
                        <div class="col-md-6 col-xl-4 col-xl-offset-1" style="padding: 0;">
                            <a href="/">
                                <img src="{{ asset('storage/img/logo5.png') }}" style="width: 100%;">
                            </a>
                        </div>
                        <div class="col-6 col-xl-7">
                            <img src="{{ asset('storage/img/company_logo.png') }}" width="100">
                            <br><span class="header-text">Employee Portal</span>
                        </div>
                    </div>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/" style="white-space: nowrap"><i class="icon-home"></i> &nbsp;HOME</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="white-space: nowrap">
                                <i class="icon-info"></i> &nbsp;UPDATES
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="/gallery" style="white-space: nowrap"><i class="icon-hourglass"></i> &nbsp;Gallery</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/manuals" style="white-space: nowrap"><i class="icon-notebook"></i> &nbsp;MANUALS</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="white-space: nowrap">
                                <i class="icon-book-open"></i> &nbsp;SERVICES
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="/services/internet">Internet</a></li>
                                <li><a class="dropdown-item" href="/services/email">Email</a></li>
                                <li><a class="dropdown-item" href="/services/system">System</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="white-space: nowrap">
                                <i class="icon-briefcase"></i> &nbsp;POLICY
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="/policies">Operational Policies</a></li>
                                <li><a class="dropdown-item" href="/itguidelines">IT Guidelines and Policy</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/services/directory" style="white-space: nowrap"><i class="icon-briefcase"></i> &nbsp;EMPLOYEE DIRECTORY</a>
                        </li>
                        @if(Auth::check())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/home') }}" style="white-space: nowrap"><i class="icon-user"></i> &nbsp;MY PROFILE</a>
                            </li>
                            {{-- @if (Auth::user()->department_id == 9)
                                <li class="nav-item">
                                    <a class="nav-link" href="/email_logs"><i class="fa fa-envelope"></i> &nbsp;EMAIL LOGS</a>
                                </li>
                            @endif --}}
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    
</div>
<style>
    .nav-item{
        margin: 0 6px 0 6px
    }
</style>