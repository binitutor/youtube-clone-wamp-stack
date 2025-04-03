<?php

include_once "db_helper.php";
$db = new DBHelper(); // new instance(copy) of the class
$videoId = $_POST['video_id'];
$userId = $_POST['user_id'];
$comment = $_POST['comment'];
// $pageUrl = $_POST['page_url'];
// save to database
if( $videoId && $userId && $comment){
    // prepare query - insert
    $insert_comment = $db->set_query( "SQL_SET_COMMENT", [$videoId, $userId, $comment] );
    $rows = $db->execute_query( $insert_comment );
    echo 'success!!';
    // refresh the page to display updated comment
    // header($pageUrl);
}



