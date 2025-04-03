<?php

include_once "db_helper.php";
include_once "utility.php";
$db = new DBHelper(); 

// ******** UPLOADING NEW VIDEO ************
function upload_video( $videoFile ){
    $videoFilename = $videoFile['name']; // .mp4
    $fn = rename_file( $videoFilename );
    $new_filename = $fn[0];    
    $file_upload_path = './../uploads/videos/' . $new_filename;   
    $relPath = './uploads/videos/' . $new_filename;

    // upload new thumbnail to the uploads folder
    $sourcePath = $videoFile['tmp_name']; // source
    $targetPath = $file_upload_path; // destination

    $uploadStatus = upload_file( $sourcePath, $targetPath, $relPath );
    // status, feedback, url
    $videoURL = $relPath; // $uploadStatus['url'];
    return [$new_filename, $videoURL];
}

function upload_thumbnail( $videoThumbnail ){
    $thumbnailFilename = $videoThumbnail['name']; // .png
    $fn = rename_file( $thumbnailFilename );
    $new_filename = $fn[0];    
    $file_upload_path = './../uploads/thumbnails/' . $new_filename;   
    $relPath = './uploads/thumbnails/' . $new_filename;

    // upload new thumbnail to the uploads folder
    $sourcePath = $videoThumbnail['tmp_name']; // source
    $targetPath = $file_upload_path; // destination

    $uploadStatus = upload_file( $sourcePath, $targetPath, $relPath );
    // status, feedback, url
    $thumbnailURL = $relPath; // $uploadStatus['url'];
    return $thumbnailURL;
}

/*
    MODIFY WAMP PHP SETTINGS to increase upload size limits
        post_max_size: 8Mb --> 128Mb
        upload_max_filesize: 8Mb --> 256Mb
        file_uploads: turned On (checked)
        memory_limit: 8Mb --> 512Mb
*/

// check the request type
if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
    // check if the post data fields are sent
    // check video file first
    if ( ( isset($_FILES['upload_videoInput'])  )
        ) {
        // check thumbnail file
        //  && $_FILES['upload_videoThumbnail']['error'] == UPLOAD_ERR_OK
        if( ( isset($_FILES['upload_videoThumbnail']) )
        ) {
            // proceed with upload
            // capture values sent from form
            $videoFile = $_FILES['upload_videoInput'];
            $videoTitle = $_POST['upload_videoTitle'];
            $videoVisibility = $_POST['videoVisibility'];
            $videoSchedule = $_POST['videoSchedule'];
            $videoDescription = $_POST['upload_videoDescription'];
            $videoThumbnail = $_FILES['upload_videoThumbnail'];
            $userId = $_POST['uploader_id'];
            $channelId = $_POST['channel_id'];
            
            // check if user is authenticated and channel is found
            if( $userId && $channelId ){
                if( $videoFile && $videoThumbnail ){
                    // upload video
                    $up = upload_video($videoFile);
                    $videoFilename = $up[0];
                    $videoURL = $up[1];

                    // upload thumbnail
                    $thumbnailURL = upload_thumbnail($videoThumbnail);

                    // check if video metadata is populated
                    if( $videoTitle && $videoDescription ){
                        // check if visibility is checked ON
                        if( $videoVisibility ){
                            // dont schedule. perform direct public upload
                            $uploadDate = date('Y-m-d'); // today
                        } else {
                            // schedule for certain date
                            $uploadDate = $videoSchedule; // scheduled date
                        }

                        // update sql database videos table
                        $upload_video = $db->set_query( 
                            "SQL_UPLOAD_VIDEO", 
                            [$videoTitle, $videoDescription, $videoURL, $thumbnailURL, 
                            25, $uploadDate, $userId, $channelId] // 8 parameters
                        );
                        $rows = $db->execute_query( $upload_video );
                        header('Location: ./../studio.php'); // redirect to studio
                    } else {
                        echo 'Unable to update database with video metadata!';
                    }

                } else {
                    echo 'Unable to capture file inputs';
                }
            } else {
                echo 'Unable to capture user and channel ids';
            }
        } else {
            echo 'Thumbnail Input file is not set!<br>' .$_FILES['upload_videoThumbnail']['error'];
            // 0 = file value not set
            // 1 = is set!
        }
    } else {
        echo 'Video Input file is not set!<br>' .$_FILES['upload_videoInput']['error'];
        // 0 = file value not set
        // 1 = is set!
    }
}  else {
    echo 'Invalid request! ' . $_SERVER['REQUEST_METHOD'];
}

