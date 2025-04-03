<?php

include_once "db_helper.php";
$db = new DBHelper(); // new instance(copy) of the class
$videoId = $_POST['video_id'];
$userId = $_POST['user_id'];
$reaction = $_POST['reaction'];
// save to database
if( $videoId && $userId && $reaction){
    // the same user shouldnt be able to enter reaction more than once!!!
    // one like or dislike per user!!!
    // check the table before saving.
    $get_reactions = $db->set_query( "SQL_GET_ALL_REACTIONS", [] );
    $rows = $db->execute_query( $get_reactions );
    $reactionExists_FLAG = false;
    foreach ($rows as $row){
        $db_video_id = $row['video_id_fk']; // 1, 2, 
        $db_user_id = $row['user_id_fk']; // 1000, 1001, ...
        $db_reaction = $row['reaction'];    // like or dislike
        if( 
            strval($videoId) == strval($db_video_id) && 
            strval($userId) == strval($db_user_id)
        ){ 
            $reactionExists_FLAG = true;
        }
    }
    if( $reactionExists_FLAG ){
        // update the reaction value
        $update_reaction = $db->set_query( "SQL_UPDATE_REACTION", [$videoId, $userId, $reaction] );
        $rows = $db->execute_query( $update_reaction );
    } else {
        // insert new reaction
        $insert_reaction = $db->set_query( "SQL_SET_REACTION", [$videoId, $userId, $reaction] );
        $rows = $db->execute_query( $insert_reaction );
    }    
    echo 'success!!';
}



