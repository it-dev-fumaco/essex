<!-- The Modal -->
<div class="modal fade" id="loginModal">
    <div class="modal-dialog">
        <div class="modal-content login-content">
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-login-form box" style="background-color: white;">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Login to Essex</h4>
                            </div>
                            <div class="form-group">
                                <div id="message"></div>
                            </div>

                            <ul class="nav nav-tabs" id="login-tabs">
                                <li class="active text-center" style="display: inline-block; width: 50%; padding: 0;">
                                    <a href="#tab-ldap" data-toggle="tab" aria-expanded="false">Login with LDAP</a>
                                </li>
                                <li class="text-center" style="display: inline-block; width: 50%; padding: 0;">
                                    <a href="#tab-biometrics" data-toggle="tab" aria-expanded="true">Login with Biometric</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane" id="tab-biometrics">
                                    <form action="/userLogin" method="POST" autocomplete="off" id="biometric-login-form">
                                        @csrf
                                        <input type="hidden" name="login_as" value="login">
                                        <div class="form-group">
                                            <div class="input-icon">
                                                <i class="icon-user"></i>
                                                <input type="text" class="form-control" name="user_id" placeholder="Biometric ID" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-icon">
                                                <i class="icon-lock-open"></i>
                                                <input type="password" class="form-control" placeholder="Password" name="password" required>
                                            </div>
                                        </div>
                                        <button class="btn btn-common log-btn" type="submit">LOG IN</button>
                                    </form>
                                </div>
                                <div class="tab-pane in active" id="tab-ldap">
                                    <form action="/userLogin" method="POST" autocomplete="off" id="ldap-login-form">
                                        @csrf
                                        <input type="hidden" name="login_as" value="ldap-login">
                                        <div class="form-group">
                                            <div class="input-icon">
                                                <i class="icon-user"></i>
                                                <input type="text" class="form-control" name="user_id" placeholder="Username" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-icon">
                                                <i class="icon-lock-open"></i>
                                                <input type="password" class="form-control" placeholder="Password" name="password" required>
                                            </div>
                                        </div>
                                        <button class="btn btn-common log-btn" type="submit">LOG IN</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #login-tabs .active a{
        background-color: white;
        color:  #566573;
        border-top: 1px solid #d5dbdb;
        border-right: 1px solid #d5dbdb;
        border-left: 1px solid #d5dbdb;
        border-bottom: 0;
        margin: 0 !important;
    }
    #login-tabs a{
        background-color: #e5e8e8;
        color:  #566573;
        border: 0;
        border-bottom: 1px solid #d5dbdb;
        margin: 0 !important;
    }
    #login-tabs a:hover{
        background-color: #f2f4f4;
    }
</style>