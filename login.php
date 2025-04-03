<!DOCTYPE html>
<html lang="en">
    <head>
        <?php 
            include_once "components/header.php";
        ?>        
        <title>YouTube Clone / Login</title>
    </head>
    <body >
        <div class="container">
            <div class="row my-5">
                <div class="col-lg-8 offset-lg-2">
                    <div class="row">
                        <div class="col-lg-4 login-left">
                            <img class="img-thumbnail" src="./assets/img/google.png" alt="Google logo">
                            <p class="display-4">Verify it is you</p>
                        </div>

                        <div class="col-lg-8 login-right">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="text-center">YouTube Clone / Sign in</h4>
                                </div>

                                <div class="card-body">
                                    <form onsubmit="validateLoginForm(event)">
                                        <h4 class="text-center text-muted fw-lighter">Sign in</h4>

                                        <div class="form-group">
                                            <label for="email">Gmail</label>
                                            <input type="email" name="email" id="email" class="form-control mb-3" required>
                                            
                                            <label for="password">Password</label>
                                            <input type="password" name="password" id="password" class="form-control mb-3" required>
                                            <i class="fa fa-eye-slash" onclick="view_password()"></i>
                                        </div>

                                        <span class="mx-3 text-center">
                                            <a href="#" style="color: #224f9c;" class="text-decoration-none">
                                                Forgot your password?
                                            </a>
                                        </span>
                                        <button type="submit" style="background: #224f9c; color: #eee;" class="btn">
                                            <i class="fa fa-sign-in"></i> Login
                                        </button>
                                    </form>
                                    <p>Don't have an account? <a href="./register.php" style="color: #224f9c;" class="text-decoration-none">Register</a></p>

                                    <!-- <button onclick="logout()" style="background:rgb(156, 52, 34); color: #eee;" class="btn">
                                        <i class="fa fa-sign-out"></i> Logout
                                    </button> -->

                                    <a href="./index.php" style="background:rgb(156, 52, 34); color: #eee;" 
                                        class="btn my-5">
                                        <i class="fa fa-youtube"></i> Go to YouTube Home
                                    </a>

                                    <div class="m-5" id="msg"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <?php 
            include_once "components/footer.php";
            include_once "components/modal-loading.php";
        ?>
    </body>
</html>