<?php

/********** WARNING !!! ************/
/*
    This page is intended for administrative use only.
            Handle carefully!
*/
session_start(); // starts browser session or resumes the one loaded
ini_set('display_errors', 1); // sets configuration option

class Authentication {
    private $db;

    public function __construct(){
        ob_start(); // turn on buffering

        // save environment vars
        $this->save_env_vars();
        
        // load them to the variables below        
        $servername = getenv('DB_HOST');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');
        $database = getenv('DB_NAME');

        // use them to connect to database
        $this->db = new mysqli($servername, $username, $password, $database);
        // check connection
        if ( $this->db->connect_error ){
            die("Connection failed: " . $this->db->connect_error);
        }        
    }

    function login(){
        extract($_POST);
        $username = $_POST['em'];
        $password = $_POST['pw']; 
        $encPassword = md5( $password ); // encrypt the password
        $authQuery = "SELECT * FROM users WHERE user_email = '" . $username . "' AND user_password = '" . $encPassword . "'";
        $result = $this->db->query( $authQuery );

        // capture response object
        $respObj = new stdClass();

        if ( $result->num_rows > 0 ){
            // user found and authenticated (pw matched)
            foreach( $result->fetch_array() as $key=>$value ){
                // authenticated!!
                if ( $key != 'user_password' && !is_numeric($key) ){
                    // save to session
                    $_SESSION['login_' . $key] = $value;
                }
                if ( $key == 'user_name' ){
                    // save to session
                    $_SESSION['full_name'] = $value;
                    $respObj->name = $value;
                }
                // update status
                $respObj->status = 200; // success http code
                $respObj->message = 'Successfully logged in.';
                $respObj->colorClass = 'text-success';
                $respObj->alertClass = 'alert alert-success';
            }
        } else {
            // authentication failed/user not found or wrong password
            // update status
            $respObj->status = 403; // forbidden http response
            $respObj->name = '';
            $respObj->message = 'Resource forbidden from access. Please enter the correct username and password.';
            $respObj->colorClass = 'text-danger';
            $respObj->alertClass = 'alert alert-danger';
        }
        // convert obj to json
        $respJSON = json_encode( $respObj );
        return $respJSON;
    }

    function logout(){
        session_destroy(); // remove the current session data
        foreach ($_SESSION as $key=>$value){
            unset($_SESSION[$key]); // remove each key value pair in session
        }
        $respObj = new stdClass();        
        $respObj->status = 200;      
        $respObj->message = 'logout request was successful';
        $respObj->alertClass = 'alert alert-success';
        $respJSON = json_encode(  $respObj );
        return $respJSON;
        // redirect to the login page
        // header('location:login.php');
    }

    function register(){
        extract($_POST);
        $userName = $_POST['full_name'];
        $userEmail = $_POST['email'];
        $password = $_POST['password'];
        $profile = $_FILES['profile']; // this is image file
        $encPassword = md5( $password ); // encrypt the password
        
        // 1. upload image and get the url
        $profileUrl = $this->upload_profile( $profile );

        // 2. create user account
        $authQuery = " INSERT INTO users (user_name, user_email, user_password, user_profile_url, user_created_at) VALUES ('$userName', '$userEmail', '$encPassword', '$profileUrl', CURRENT_TIMESTAMP ) ";
        $result = $this->db->query( $authQuery );
        // returns: "Query OK, 1 row affected" string statement
        // make it return the user id that is created with Auto increment
        // RETURNING id caused error. 
        // user_id always increass by 1. so capturing the last row with highest id number also works!


        // capture response object
        $respObj = new stdClass();

        if ( $result ){
            // get all user accounts in decreasing order from high to low.
            // then only return the first row of them. meaning the highest value row.
            
            $authQuery = "SELECT * FROM users ORDER BY user_id DESC LIMIT 1";
            $res = $this->db->query( $authQuery );
            if ( $res->num_rows > 0 ) {
                foreach( $res->fetch_array() as $key=>$value ) {
                    // update session
                    if ( $key != 'user_password' && !is_numeric($key) ){
                        // save to session
                        $_SESSION['login_' . $key] = $value;
                    }
                    if ( $key == 'user_name' ){
                        // save to session
                        $_SESSION['full_name'] = $value;
                        $respObj->name = $value;
                    }
                    // update status
                    $respObj->status = 200; // success http code
                    $respObj->message = 'Successfully created user account.';
                    $respObj->colorClass = 'text-success';
                    $respObj->alertClass = 'alert alert-success';
                }
            }
        } else {
            // unable to create user account
            // update status
            $respObj->status = 403; // forbidden http response
            $respObj->name = ''; // user full name
            $respObj->message = 'SORRY :) We are unable to create your user account at this time. Please try again later!';
            $respObj->colorClass = 'text-danger';
            $respObj->alertClass = 'alert alert-danger';
        }
        // convert obj to json
        $respJSON = json_encode( $respObj );
        return $respJSON;
    }

    function __destruct(){
        // removes db session
        $this->db->close();
        // stops buffering
        ob_end_flush();
    }

    private function save_env_vars(){
        // read environment variables
        $env = file_get_contents(__DIR__ . "/../.env"); // one string
        $env_array = explode("\n", $env); // 4 string values
        // save values to php environment
        foreach ( $env_array as $val ){
            preg_match("/([^#]+)\=(.*)/", $val, $matches); // checks allows characters
            if ( isset( $matches[2] ) ){
                putenv( trim($val) ); // saves 4 items to environment
            }
        }
    }

    private function upload_profile( $profilePic ){
        $profilePicFilename = $profilePic['name']; // .png
        $profilePicTempFilename = $profilePic['tmp_name']; // .png
        $clean_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $profilePicFilename); 
        $extension = pathinfo($profilePicFilename, PATHINFO_EXTENSION); // .png
        // rename file to avoid duplicate file upload
        $new_filename = $clean_name . '__' . time() . '.' . $extension; // adds microseconds
   
        $file_upload_path = './../uploads/users/' . $new_filename;  

        // upload new profile to the uploads folder
        $sourcePath = $profilePicTempFilename; // source
        $targetPath = $file_upload_path; // destination
        if(move_uploaded_file( $sourcePath, $targetPath )) {
            // file file uploaded successfully... set the url 
            $relPath = './uploads/users/' . $new_filename;
        } else { 
            $relPath = './uploads/users/default-avatar.jpg';
        }
        return $relPath;
    }
}







