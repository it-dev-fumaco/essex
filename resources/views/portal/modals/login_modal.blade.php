<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Login to Essex</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div id="message"></div>
                </div>

                <ul class="nav nav-tabs" id="login-tabs">
                    {{-- <li class="nav-item m-0 col-6">
                        <a class="nav-link active" aria-current="page" data-toggle="tab" data-target="#ldap-login">Login with LDAP</a>
                    </li> --}}
                    <li class="nav-item m-0 col-6">
                        <a class="nav-link active" aria-current="page" data-toggle="tab" data-target="#biometric-login">Login with Biometric</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="ldap-login" class="container tab-pane" style="padding: 8px 0 0 0;">
                        <form action="/userLogin" method="POST" autocomplete="off" id="ldap-login-form">
                            @csrf
                            <input type="hidden" name="login_as" value="ldap-login">
                            <div class="form-group">
                                <div class="input-icon">
                                    <i class="icon-user"></i>
                                    <input type="text" class="form-control" name="user_id" placeholder="Username" required style="font-size: 10pt;">
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="input-icon">
                                    <i class="icon-lock-open"></i>
                                    <input type="password" class="form-control" placeholder="Password" name="password" required style="font-size: 10pt;">
                                </div>
                            </div>
                            <br>
                            <button class="btn btn-common log-btn w-100" type="submit">LOG IN</button>
                        </form>
                    </div>
                    <div id="biometric-login" class="container tab-pane active" style="padding: 8px 0 0 0;">
                        <form action="/userLogin" method="POST" autocomplete="off" id="biometric-login-form">
                            @csrf
                            <input type="hidden" name="login_as" value="login">
                            <div class="form-group">
                                <div class="input-icon">
                                    <i class="icon-user"></i>
                                    <input type="text" class="form-control" name="user_id" placeholder="Biometric ID" required style="font-size: 10pt;">
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="input-icon">
                                    <i class="icon-lock-open"></i>
                                    <input type="password" class="form-control" placeholder="Password" name="password" required style="font-size: 10pt;">
                                </div>
                            </div>
                            <br>
                            <button class="btn btn-common log-btn w-100" type="submit">LOG IN</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #login-tabs .nav-item{
        background-color: #e5e8e8;
        color:  #566573;
        border: 0;
        text-align: center;
    }

    #login-tabs .nav-item:hover{
        background-color: #f2f4f4 !important;
    }
</style>