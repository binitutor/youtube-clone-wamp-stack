<?php

include_once "db_helper.php";
$db = new DBHelper(); // new instance(copy) of the class
$videoId = $_POST['video_id'];
$userId = $_POST['user_id'];
$commentId = $_POST['comment_id'];
$comm_reaction = $_POST['reaction'];
// save to database
if( $videoId && $userId && $commentId && $comm_reaction){
    // the same user shouldnt be able to enter reaction more than once!!!
    // one like or dislike per user!!!
    // check the table before saving.
    $get_comm_reactions = $db->set_query( "SQL_GET_ALL_COMMENT_REACTIONS", [] );
    $rows = $db->execute_query( $get_comm_reactions );
    $reactionExists_FLAG = false;
    foreach ($rows as $row){
        $db_video_id = $row['video_id_fk']; // 1, 2, 
        $db_user_id = $row['user_id_fk']; // 1000, 1001, ...
        $db_comment_id = $row['comment_id_fk']; // 1, 2, 
        $db_reaction = $row['reaction'];    // like or dislike
        if( 
            strval($videoId) == strval($db_video_id) && 
            strval($userId) == strval($db_user_id)&& 
            strval($commentId) == strval($db_comment_id)
        ){ 
            $reactionExists_FLAG = true; // reacting to the same comment
        }
    }
    if( $reactionExists_FLAG ){
        // update the reaction value
        $update_comm_reaction = $db->set_query( "SQL_UPDATE_COMM_REACTION", [$videoId, $userId, $commentId, $comm_reaction] );
        $rows = $db->execute_query( $update_comm_reaction );
        echo 'updated the reaction value';
    } else {
        // insert new comment reaction
        $insert_comm_reaction = $db->set_query( "SQL_SET_COMM_REACTION", [$videoId, $userId, $commentId, $comm_reaction] );
        $rows = $db->execute_query( $insert_comm_reaction );
        echo 'insert new comment reaction';
    }    
    // echo 'success!!';
}



