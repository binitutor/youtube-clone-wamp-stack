<?php

/********** AUTHENTICATION ************/
include "admin_class.php";

// call adminstration class
$auth = new Authentication(); // instance of auth class

// attempt to login
$action = $_POST['action']; 
if ( $action== 'login' ){ // call it only if the action type is - login
    $login_status = $auth->login(); // success/failed
    if ( $login_status ){
        // return success or failed response status
        echo $login_status; // json response
    }
}
if ( $action== 'logout' ) { // call it only if the action type is - logout
    $logout_status = $auth->logout(); // success/failed
    if ( $logout_status ){
        echo $logout_status; // json response
    }
}
if ( $action== 'register' ) { // call it only if the action type is - register
    $register_status = $auth->register(); // success/failed
    if ( $register_status ){
        echo $register_status; // json response
    }
}







