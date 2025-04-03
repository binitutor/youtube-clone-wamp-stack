<?php

include_once "db_helper.php";
$db = new DBHelper();
$userId = $_POST['user_id'];

if ( $userId ){
    //using uid, get channel id
    $get_channel = $db->set_query( 
        "SQL_GET_CHANNEL_BY_USER_ID", 
        [$userId] 
    );
    $rows = $db->execute_query( $get_channel );
    foreach( $rows as $row){
        $channel_id = $row['channel_id'];
        $channel_name = $row['channel_name'];
        $channel_description = $row['channel_description'];
        echo $channel_id;
        break; // end the loop
    }
}




