<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header">Sign In</h5>
                <div class="card-body">
                    <form>
                        <div id="email-form" class="form-group">
                            <label for="inputEmail">Email address</label>
                            <input type="email" class="form-control" id="email" aria-describedby="emailHelp"
                                placeholder="Enter email">
                        </div>
                        <div id="pass-form" class="form-group">
                            <label for="inputPassword">Password</label>
                            <input type="password" class="form-control" id="pass"
                                placeholder="Password - min 6 symbols">
                        </div>
                        <a id="login" type="submit" class="btn btn-success">Login</a>
                        <hr>
                        <button id="google-auth" type="button" class="btn btn-danger">Login with Google</button>
                        <hr>
                        <button type="button" class="btn btn-primary"
                            onclick="location.href='register'">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="module" src="/z1/frontend/js/login.js"></script>