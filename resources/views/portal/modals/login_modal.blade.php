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
                            <form action="/userLogin" method="POST" autocomplete="off">
                                @csrf
                                <div class="form-group">
                                    <div class="input-icon">
                                        <i class="icon-user"></i>
                                        <input type="text" class="form-control" name="user_id" placeholder="Access ID" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-icon">
                                        <i class="icon-lock-open"></i>
                                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="rememberme" value="forever"> Remember me
                                </div>
                                <button class="btn btn-common log-btn" type="submit">LOG IN</button>
                            </form>
                            <ul class="form-links">
                                <li class="text-center"><a href="#">Don't have an account?</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>