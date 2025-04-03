<?php

include_once "db_helper.php";
include_once "utility.php";
$db = new DBHelper(); 

// ******** CREATE NEW VIDEO ************

$channelName = $_POST['channel-name'];
$channelDescription = $_POST['channel-description'];
$channelOwnerId = $_POST['channel_owner_id'];
$channelCreatedDate = date('Y-m-d'); // today
// $today = new DateTime(); // date object must be converted to string
// $channelCreatedDate = $today->format('Y-m-d'); // 2025-04-02

// echo '
//     Name: '. $channelName .'<br>
//     Description: '. $channelDescription .'<br>
//     OwnerId: '. $channelOwnerId .'<br>
//     CreatedDate: '. $channelCreatedDate .'<br>
// ';

// check if the values are set
if ($channelName && $channelDescription && $channelOwnerId){
    // check if the channel exists using the userId
    $channel_Exists = $db->set_query( 
        "SQL_GET_CHANNEL_BY_USER_ID", 
        [$channelOwnerId]
    );
    $rows = $db->execute_query( $channel_Exists );
    if( count($rows) > 0 ){
        // channel exists. redirect to studio
        header('Location: ./../studio.php'); // redirect to studio
    } else {
        // create channel 
        $create_channel = $db->set_query( 
            "SQL_CREATE_CHANNEL", 
            [$channelName, $channelDescription, $channelOwnerId, $channelCreatedDate]
        );
        $rows = $db->execute_query( $create_channel );
        header('Location: ./../studio.php'); // redirect to studio
    }
}

