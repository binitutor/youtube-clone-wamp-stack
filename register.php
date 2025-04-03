<!DOCTYPE html>
<html lang="en">
    <head>
        <?php 
            include_once "components/header.php";
        ?>        
        <title>YouTube Clone / Register</title>
    </head>
    <body >
        <div class="container">
            <div class="row my-5">
                <div class="col-lg-8 offset-lg-2">
                    <div class="row"> 

                        <div class="col-lg-4 login-left">
                            <img class="img-thumbnail" src="./assets/img/google.png" alt="Google logo">
                            <p class="display-4">Register new user account.</p>
                        </div>

                        <div class="col-lg-8 login-right">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="text-center">YouTube Clone / Register</h4>
                                </div>

                                <div class="card-body">
                                            
                                    <!-- 
                                    user_name
                                    user_email
                                    user_password
                                    user_profile_url -- upload pic
                                    -->
                                    <form onsubmit="validateRegisterForm(event)">
                                        <h4 class="text-center text-muted fw-lighter">Register</h4>

                                        <div class="form-group">
                                            <label for="full_name">Full name</label>
                                            <input type="text" name="full_name" id="full_name" 
                                                class="form-control mb-3" required>
                                            
                                            <label for="email">Gmail</label>
                                            <input type="email" name="email" id="email" class="form-control mb-3" required>
                                            
                                            <label for="password">Password</label>
                                            <input type="password" name="password" id="password" class="form-control mb-3" required>
                                            <i class="fa fa-eye-slash" onclick="view_password()"></i>
                                            <br><br>

                                            <!-- confirm password -->
                                            <label for="c_password">Confirm Password</label>
                                            <input type="password" id="c_password" class="form-control mb-3" required>
                                            <i class="fa fa-eye-slash" onclick="view_password()"></i>

                                            <!-- profile picture -->
                                            <label for="user_profile">Upload profile</label>
                                            <input type="file" name="user_profile" id="user_profile" 
                                                class="form-control my-3">

                                        </div>
                                        <button type="submit" style="background: #224f9c; color: #eee;" 
                                            class="btn mt-4">
                                            <i class="fa fa-sign-in"></i> Register
                                        </button>
                                    </form>

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