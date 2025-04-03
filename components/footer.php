<footer>
    <small>
        This clone website is intended for educational purposes only.
        Utilizing the content for the purposes other than learning website development is unlawful!
    </small>
</footer>

<div id="hidden_vals" style="display: none;" class="d-none"> 
    <?php
        // check if user id is set!
        if( isset( $_SESSION['login_user_id'] ) ){
            $full_name = $_SESSION['full_name'];
            $email = $_SESSION['login_user_email'];
            $profilePic = $_SESSION['login_user_profile_url'];
            echo '<span id="login_user_id">'. $_SESSION['login_user_id'] .'</span>';
            echo '<span id="login_user_name">'. $full_name .'</span>';
            echo '<span id="login_user_email">'. $email .'</span>';
            echo '<span id="login_user_profile_url">'. $profilePic .'</span>';
        } else {
            echo '<span id="login_user_id"></span>';
            echo '<span id="login_user_name"></span>';
            echo '<span id="login_user_email"></span>';
            echo '<span id="login_user_profile_url"></span>';
        }

        // check if video id is set in the url path
        $url = $_SERVER['REQUEST_URI'];
        $url_video_id = 0; // default
        // if video id is passed as url param
        if ( !is_null( parse_url($url, PHP_URL_QUERY) ) &&
            str_contains( parse_url($url, PHP_URL_QUERY), '=' ) ) {
            $urlArray = explode( '=', parse_url($url, PHP_URL_QUERY) ); // [arg=val, arg2=val2 ...]
            if( $urlArray[0] == 'video_id' ){ // if key is found
                echo '<span id="url_video_id">'. $urlArray[1] .'</span>';
            } else{
                echo '<span id="url_video_id"></span>';
            }
        } else{
            echo '<span id="url_video_id"></span>';
        }


    ?>     
</div>

<?php
    include_once 'components/prompt-login.php';
            
    include_once "components/modal-loading.php";
    include_once 'components/modal-v-editor.php';
    include_once 'components/modal-v-upload.php';
    include_once 'components/modal-create-channel.php';
    include_once 'components/modal-go-live.php';
?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="./assets/js/main_script.js"></script>
