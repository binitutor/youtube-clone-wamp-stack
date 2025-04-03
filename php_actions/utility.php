<?php

/**** FUNCTIONS *****/

function calc_upload_dt_interval($upload_date){
    $up_dt = new DateTime( $upload_date ); // convert string to date object
    $today = new DateTime( ); // 2025-03-24
    $interval = $up_dt->diff( $today );
    $total_days_count = $interval->days; // 1000 days
    $interval_years = $interval->y; // 1 year
    $interval_months = $interval->m; // 3 months
    $interval_days = $interval->d; // 20 days
    $created_date_str = "";
    if ( $total_days_count < 30 ){
        $created_date_str .= $interval_days . " days ago";
    } else if ( $total_days_count < 365 ){
        if ( $interval_months > 0 ){
            $created_date_str .= $interval_months . " months";
            if ( $interval_days > 0 ) {
                $created_date_str .= ", " . $interval_days . " days";
            }
            $created_date_str .= " ago";
        } else {
            $created_date_str .= $interval_days . " days ago";
        }                    
    } else {
        $created_date_str .= $interval_years . " year(s)";
        if ( $interval_months > 0 ){
            $created_date_str .= ", " . $interval_months . " months";
            if ( $interval_days > 0 ) {
                $created_date_str .= ", " . $interval_days . " days";
            }
        }
        $created_date_str .= " ago";
    }
    return $created_date_str; // 1 year, 3 months, 19 days ago
}

function get_uploader_thumbnail($userId, $db){
    $get_users = $db->set_query( "SQL_GET_USER_BY_ID", [$userId] ); 
    $rows = $db->execute_query( $get_users );
    foreach ( $rows as $user ){
        $userName = $user['user_name'];
        $userEmail = $user['user_email'];
        $userPassword = $user['user_password'];
        $userProfileUrl = $user['user_profile_url'];
        return $userProfileUrl;
    }
}

function get_uploader_info($userId, $db){
    $get_user = $db->set_query( "SQL_GET_USER_BY_ID", [$userId] ); 
    $rows = $db->execute_query( $get_user );
    $user_info = [];
    foreach ( $rows as $user ){
        $userName = $user['user_name'];
        $userEmail = $user['user_email'];
        $userPassword = $user['user_password'];
        $userProfileUrl = $user['user_profile_url'];
        array_push( 
            $user_info, 
            [ $userId, $userName, $userEmail, 
            $userPassword, $userProfileUrl] 
        );
        return $user_info;
    }
}

function get_view_count($videoId, $db){
    $get_view_count = $db->set_query( "SQL_GET_VIEWS_BY_VID_ID", [$videoId] ); 
    $rows = $db->execute_query( $get_view_count );
    foreach ( $rows as $view ){
        $viewCount = $view['view_count'];
        return $viewCount;
    }
}

function get_channel_name($userId, $db){
    $get_channel = $db->set_query( "SQL_GET_CHANNEL_BY_USER_ID", [$userId] ); 
    $rows = $db->execute_query( $get_channel );
    foreach ( $rows as $channel ){
        $channelName = $channel['channel_name'];
        return $channelName;
    }
}

function get_video_comments($videoId, $db){
    // capture user id, capture the comment
    $comments = [];
    $get_comments = $db->set_query( "SQL_GET_COMMENTS_BY_VIDEO_ID", [$videoId] ); 
    $rows = $db->execute_query( $get_comments );
    foreach ( $rows as $channel ){
        $userId = $channel['user_id_fk'];
        $comment = $channel['comment_text'];
        $comment_date = $channel['comment_date'];
        $comment_id = $channel['comment_id'];
        array_push( $comments, [$userId, $comment, $comment_date, $comment_id]);
    }
    return $comments;
}

function get_comment_reactions( $videoId, $comment_id, $db ) {
    // return reaction counts for single comment
    $get_comment_reactions = $db->set_query( "SQL_GET_COMM_REACTIONS_BY_VIDEO_ID_AND_COMMENT_ID", [$videoId, $comment_id] ); 
    $rows = $db->execute_query( $get_comment_reactions );
    $likes = 0;
    $dislikes = 0;
    foreach ( $rows as $comm_reaction ){
        $reaction = $comm_reaction['reaction'];
        if ( $reaction == 'like' ) {
            $likes += 1;
        } else if ( $reaction == 'dislike' ) {
            $dislikes += 1;
        }         
    }
    $comment_reactions = [$likes, $dislikes];
    return $comment_reactions;
}

function get_video_reactions($videoId, $db){
    $reactions = $db->set_query( "SQL_GET_VID_REACTIONS_BY_VIDEO_ID", [$videoId] ); 
    $rows = $db->execute_query( $reactions );
    $likes = 0;
    $dislikes = 0;
    foreach ( $rows as $vid_reaction ){
        $reaction = $vid_reaction['reaction'];
        if ( $reaction == 'like' ) {
            $likes += 1;
        } else if ( $reaction == 'dislike' ) {
            $dislikes += 1;
        }         
    }
    $video_reactions = [$likes, $dislikes];
    return $video_reactions;
}

function round_to_nearest_thousand($num){
    if ($num >= 1000){
        $x = round($num);
        $x_number_format = number_format($x); // 1,234,234
        $x_array = explode(',', $x_number_format);
        $x_parts = ['k', 'm', 'b', 't']; // kill, million ...
        $x_count_parts = count( $x_array ) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ( (int)$x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '' );
        $x_display .= $x_parts[ $x_count_parts - 1 ];
        return $x_display;
    }
    return $num;
}

function get_comment_replies( $videoId, $comment_id, $db ) {    
    // return reaction counts for single comment
    $get_comment_replies = $db->set_query( 
        "SQL_GET_COMM_REPLIES_BY_VIDEO_ID_AND_COMMENT_ID", 
        [$videoId, $comment_id] 
    ); 
    $rows = $db->execute_query( $get_comment_replies );
    $comment_replies = [];
    foreach ( $rows as $comm_reply ){
        $userId = $comm_reply['user_id_fk'];
        $reply = $comm_reply['reply'];
        $replys_date = $comm_reply['replys_date'];        
        array_push($comment_replies, [$userId, $reply, $replys_date] );
    }
    return $comment_replies;
}

function get_video_tags($videoId, $db){    
    // video_tags - for single video
    $get_video_tags = $db->set_query( "SQL_GET_TAGS_BY_VIDEO_ID", [$videoId] ); 
    $rows = $db->execute_query( $get_video_tags );
    foreach ( $rows as $video ){
        $video_tags = $video['video_tags']; // Bible,God
        // check if tags are set for the video
        if ( $video_tags !== null ){
            $video_tags_array = explode(',', $video_tags);
            return $video_tags_array;
        } else {
            return [];
        }
    }    
}                        

/**** STUDIO FUNCTIONS *****/
function rename_file( $original_filename ){
    // remove file path & extension
    $clean_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $original_filename); 
    $extension = pathinfo($original_filename, PATHINFO_EXTENSION); // .png
    // rename file to avoid duplicate file upload
    $new_filename = $clean_name . '__' . time() . '.' . $extension; // adds microseconds
    $file_upload_path = './../uploads/thumbnails/' . $new_filename;
    $relative_path = './uploads/thumbnails/' . $new_filename;
    // echo '
    // original filename - ' . $filename . '<br>
    // clean_name - ' . $clean_name . '<br>
    // extension - ' . $extension . '<br>
    // new filename - ' . $new_filename . '<br>
    // ';
    return [ $new_filename, $file_upload_path, $relative_path];
}

function upload_file( $sourcePath, $targetPath, $relPath ){
    $uploadStatus = array(); // status, feedback, url
    if(move_uploaded_file( $sourcePath, $targetPath )) {
        // if uploaded successfully...
        $uploadStatus['status'] = true;
        $uploadStatus['feedback'] = 'File was uploaded successfully.';
        $uploadStatus['url'] = $relPath;
    } else {
        $uploadStatus['status'] = false;
        $uploadStatus['feedback'] = 'Unable to upload the file!';
        $uploadStatus['url'] = './uploads/thumbnails/placeholder.png';
    }
}

