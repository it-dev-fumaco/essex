<div class="row">
    <div class="col-3">
        <div class="row" style="display: flex; justify-content: center; align-items: center;">
            <div class="col-md-4 col-md-offset-1" style="padding: 0;">
                <a href="/">
                    <img src="{{ asset('storage/img/logo5.png') }}" style="width: 100%;">
                </a>
            </div>
            <div class="col-md-7">
                <img src="{{ asset('storage/img/company_logo.png') }}" width="100"><br><span class="header-text"
                    style="">Employee Portal</span>
            </div>
        </div>
    </div>
    <div class="col-xl-7 col-lg-9" style="display: flex; justify-content: center; align-items: center;">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/"><i class="icon-home"></i> &nbsp;HOME</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="icon-info"></i> &nbsp;UPDATES
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="/gallery"><i class="icon-hourglass"></i> &nbsp;Gallery</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/manuals"><i class="icon-notebook"></i> &nbsp;MANUALS</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="icon-book-open"></i> &nbsp;SERVICES
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="/services/internet">Internet</a></li>
                                <li><a class="dropdown-item" href="/services/email">Email</a></li>
                                <li><a class="dropdown-item" href="/services/system">System</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="icon-briefcase"></i> &nbsp;MEMORANDUM / POLICY
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="/policies">Operational Policies</a></li>
                                <li><a class="dropdown-item" href="/itguidelines">IT Guidelines and Policy</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/services/directory"><i class="icon-briefcase"></i> &nbsp;EMPLOYEE DIRECTORY</a>
                        </li>
                        @if(Auth::user())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/home') }}"><i class="icon-user"></i> &nbsp;MY PROFILE</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    {{-- @if(Auth::user())
        <div class="col-2">
            <div class="col-10 offset-2" style="display: flex; justify-content: center; align-items: center;">
                <a href="{{ url('/home') }}" class="btn dashboard-btn"><i class="fa fa-users"></i> &nbsp;Essex Dashboard</a>
            </div>
        </div>
    @endif --}}
    
</div>
<style>
    .nav-item{
        margin: 0 6px 0 6px
    }
</style>