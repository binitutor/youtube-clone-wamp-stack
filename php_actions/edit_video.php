<?php

include_once "db_helper.php";
include_once "utility.php";
$db = new DBHelper(); // new instance(copy) of the class
$videoId = $_POST['video_id'];
$videoAction = $_POST['video_action']; // save, get, temp_upload
// echo '
//     video id - '. $videoId .'
//     action - '. $videoAction .'
// ';

// on edit mode, if we DONT change the thumbnail it overwrites the existing one.
// but we want to keep the existing one unless a new one is set!!


if( $videoAction == 'save' ){
    
    // the request is coming from editor modal
    // capture modified values
    $videoTitle = $_POST['edit_videoTitle'];
    $videoDescription = $_POST['edit_videoDescription'];
    $thumbnail = $_FILES['edit_videoThumbnail_input'];
    $filename = $thumbnail['name'];
    $fn = rename_file( $filename );
    $new_filename = $fn[0];
    $file_upload_path = $fn[1];   
    $relPath = $fn[2];
    
    // check if the thumbnail file is set
    if( isset($thumbnail) ){
        // upload new thumbnail to the uploads folder
        $sourcePath = $thumbnail['tmp_name']; // source
        $targetPath = $file_upload_path; // destination
        $uploadStatus = upload_file( $sourcePath, $targetPath, $relPath ); // status, feedback, url
        $videoThumbnailUrl = $relPath; // $uploadStatus['url'];
    } else {
        // if thumbnail is not changed by user/uploader
        $get_video = $db->set_query( 
            "SQL_GET_VIDEO_BY_ID", 
            [$videoId] 
        );
        $rows = $db->execute_query( $get_video );
        foreach($rows as $row){
            // get the existing thumbnail url
            $videoThumbnailUrl = $row['video_thumbnail_url'];
        }
    }
    

    // update the video info in db
    if( $videoId && $videoTitle && $videoDescription ) {
        // get the video using the id
        $update_video = $db->set_query( 
            "SQL_UPDATE_VIDEO_BY_ID", 
            [$videoId, $videoTitle, $videoDescription, $videoThumbnailUrl] 
        );
        $rows = $db->execute_query( $update_video );
        // echo 'success';
        // redirect to the YouTube Studio page
        header('Location: ./../studio.php');
    }

} else if( $videoAction == 'get' ) {
    // the request is coming from js
    // get video
    if( $videoId){
        // get the video using the id
        $get_video = $db->set_query( "SQL_GET_VIDEO_BY_ID", [$videoId] );
        $rows = $db->execute_query( $get_video );
        $video_data = new stdClass(); // object k->v
        foreach ($rows as $row){        
            $video_data->video_id = $row['video_id'];  
            $video_data->video_title = $row['video_title']; 
            $video_data->video_description = $row['video_description']; 
            $video_data->video_url = $row['video_url']; 
            $video_data->video_thumbnail_url = $row['video_thumbnail_url']; 
            $video_data->video_created_at = $row['video_created_at'];       
            $video_data->video_uploader = $row['video_uploader_id_fk'];       
            $video_data->video_tags = $row['video_tags'];       
            $video_data->channel_id = $row['channel_id_fk'];     
        }
        $respJSON = json_encode( $video_data ); // convert object to json
        echo $respJSON;
    }
} else if( $videoAction == 'temp_upload' ) {
    // upload to temp folder
    $tmp_thumbnail = $_FILES['tmp_thumbnail'];
    $filename = $tmp_thumbnail['name'];
    $fn = rename_file( $filename );
    $new_filename = $fn[0];    
    $file_upload_path = './../uploads/tmp/' . $new_filename;   
    $relPath = './uploads/tmp/' . $new_filename;

    // upload new thumbnail to the uploads folder
    $sourcePath = $tmp_thumbnail['tmp_name']; // source
    $targetPath = $file_upload_path; // destination
    $uploadStatus = upload_file( $sourcePath, $targetPath, $relPath ); // status, feedback, url
    
    // echo '
    //     filename - '. $filename .'<br>
    //     new_filename - '. $new_filename .'<br>
    // ';
    $tempObj = new stdClass();
    $tempObj->relPath = $relPath;
    $tempObj->filename = $new_filename;
    echo json_encode( $tempObj );

}






