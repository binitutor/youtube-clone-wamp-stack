<!DOCTYPE html>
<html lang="en">
    <head>
        <?php 
            include_once "components/header.php";
        ?>
        <title>YouTube Clone / Studio</title>
    </head>
    <body>
        <?php 
            include_once "components/nav.php";

            // set studio true in session
            $_SESSION['studio'] = true;
            include_once "components/sidebar-left.php"; // load the sidebar

            include_once "php_actions/db_helper.php";
            include_once "php_actions/utility.php";
            $db = new DBHelper(); // new instance(copy) of the class
            
            // for the channel, get all videos  
            // each channel is owned by a single user 
            // from login, capture user id = channel_owner_fk 
            $channel_videos = '';       
            if( isset( $_SESSION['login_user_id'] ) ){
                // user is loggged in
                $channel_owner_id = $_SESSION['login_user_id'];
                // check if the owner has created a channel from channels table.
                // if id is found in channel_owner_fk, channel is found.
                $get_channel_info = $db->set_query( "SQL_GET_CHANNEL_BY_USER_ID", [$channel_owner_id] );
                $rows = $db->execute_query( $get_channel_info ); // returns info about the channel
                if( count($rows) > 0 ){ // channel found
                    // if channel found, load all videos related to (uploaded to) the channel
                    foreach( $rows as $row ){
                        $channel_id = $row['channel_id'];
                        $channel_name = $row['channel_name'];
                        $channel_description = $row['channel_description'];
                        $channel_created_at = $row['channel_created_at'];

                        // channel may or may not have uploaded videos yet!
                        $get_channel_videos = $db->set_query( 
                            "SQL_GET_ALL_CHANNEL_VIDEOS", [$channel_id] 
                        );
                        $video_rows = $db->execute_query( $get_channel_videos );
                        
                        if( count($video_rows) > 0 ) {
                            // videos found
                            $counter = 0;
                            foreach( $video_rows as $video ){
                                $counter++;
                                $video_id = $video['video_id'];
                                $video_title = $video['video_title'];
                                $video_thumbnail_url = $video['video_thumbnail_url'];
                                $video_upload_date = $video['video_created_at'];

                                // from views table, get view counts for each video.
                                // $video_views = $video['video_created_at'];

                                // check if video id exists in views table
                                // if yes, get the views count
                                // if not, create new entry and set the count to 0
                                $get_views = $db->set_query( 
                                    "SQL_GET_VIEWS_BY_VIDEO_ID", [$video_id] 
                                );
                                $views_rows = $db->execute_query( $get_views );
                                if ( count($views_rows) > 0 ){
                                    $view_count = $views_rows[0]['view_count'];
                                } else {
                                    $view_count = 0;
                                    // create new entry
                                    $initialize_views = $db->set_query( 
                                        "SQL_INITIALIZE_VIEWS_FOR_VIDEO_ID", [$video_id] 
                                    );
                                    $rows = $db->execute_query( $initialize_views );
                                }

                                // count comments
                                $comments = get_video_comments($video_id, $db); 
                                $comment_count = strval(count($comments)) . ' comments';

                                // get all reactions for the video
                                // calculate like vs dislike proportion
                                $reactions = get_video_reactions($video_id, $db);
                                $likes = $reactions[0];
                                $dislikes = $reactions[1];
                                $total_reactions = $likes + $dislikes; //  100%                            
                                if( $total_reactions != 0){
                                    $proportion = strval(($likes * 100) / $total_reactions) . '% liked';
                                } else {
                                    $proportion = 'No reactions found!';
                                }
                                /*
                                    if 5 people like the video, and 5 people dislike it
                                    it means 50% of the reactions were like!
                                    tot = 10 
                                    lik = 5

                                    10 reactions = 100%
                                    5 likes = x
                                    x = (5 likes * 100)/10 = 50%
                                    
                                */

                                $channel_videos .= '
                                <tr>
                                    <td>'. strval($counter) .'</td>
                                    <td colspan="3">
                                        <div class="row">
                                            <div class="col col-md-4">
                                                <img src="'. $video_thumbnail_url .'" width="300px" alt="video thumbnail" class="rounded img-fluid">
                                            </div>
                                            <div class="col col-md-8">
                                                <p>'. $video_title .'</p>
                                                <div class="video-actions">

                                                    <i class="fa fa-pencil mx-2" title="Edit title and description"
                                                        onclick="edit_video(event, '. $video_id .')" 
                                                        data-bs-toggle="modal" data-bs-target="#videoEditorModal"
                                                    ></i>
                                                    
                                                    <a href="./?video_id='. $video_id .'" target="_blank" class="text-danger  mx-2">
                                                        <i class="fa fa-youtube" title="Watch on YouTube"></i>
                                                    </a>
                                                    <i class="fa fa-trash mx-2" title="Delete video"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Public</td>
                                    <td>no restriction</td>
                                    <td>'. $video_upload_date .'</td>
                                    <td>'. $view_count .'</td>
                                    <td>'. $comment_count .'</td>
                                    <td>'. $proportion .'</td>
                                </tr>
                                ';
                            }
                        } else {
                            // not found
                            $channel_videos = '
                            <tr>
                                <td colspan="10" class="display-6 text-center p-5">
                                    <div class="alert alert-warning" role="alert">
                                        You have not uploaded any videos yet!!
                                    </div>  
                                    <button class="btn btn-sm btn-outline-secondary"
                                        onclick="load_channel_metadata()"
                                        data-bs-toggle="modal" data-bs-target="#videoUploadModal"
                                        >
                                        Start uploading <i class="fa fa-plus"></i>
                                    </button>                        
                                </td>
                            </tr>';
                        }
                        
                    }
                    
                } else { // user doesnt own channel
                    // prompt user to create new channel
                    $channel_videos = '
                    <tr>
                        <td colspan="10" class="display-6 text-center p-5">
                            <div class="alert alert-warning" role="alert">
                                You have not created a YouTube channel yet!!
                            </div>     
                            <button class="btn btn-sm btn-outline-secondary"
                                onclick="create_new_channel()"
                                data-bs-toggle="modal" data-bs-target="#createChannelModal"
                                >
                                Create a new channel <i class="fa fa-plus"></i>
                            </button>                 
                        </td>
                    </tr>';
                }
            } else { 
                $channel_videos = '
                <tr>
                    <td colspan="10" class="display-6 text-center p-5">
                        <div class="alert alert-warning" role="alert">
                            Please sign in to see your uploaded videos!!
                        </div>                        
                    </td>
                </tr>';
            }                   
        ?>
        <div class="container-fluid">
            <div class="row bg-body-secondary">
                <div class="col col-md-10 offset-md-2 my-5 pt-5">
                    <div class="studio-header">
                        <h3>YouTube Studio</h3>
                        <p>Dashboard</p>
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="#" class="nav-link active">Videos</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">Shorts</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">Live</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">Posts</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">Playlists</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">Podcasts</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">Promotions</a>
                            </li>
                        </ul>
                    </div>

                    <div class="studio-content pt-5">
                        <table class="table">
                            <thead class="sticky-top bg-white">
                                <tr>
                                    <th>#</th>
                                    <th colspan="3">Video</th>
                                    <th>Visibility</th>
                                    <th>Restriction</th>
                                    <th>Date</th>
                                    <th>Views</th>
                                    <th>Comments</th>
                                    <th>Likes/Dislikes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    echo $channel_videos;
                                ?>

                                <!-- <tr>
                                    <td>1</td>
                                    <td colspan="3">
                                        <div class="row">
                                            <div class="col col-md-4">
                                                <img src="./uploads/thumbnails/tmb-bible.png" width="300px" alt="video thumbnail" class="rounded img-fluid">
                                            </div>
                                            <div class="col col-md-8">
                                                <p>Overview of the Entire Bible in 17 Minutes!</p>
                                                <div class="video-actions">
                                                    <i class="fa fa-pencil mx-2" title="Edit title and description"></i>
                                                    <i class="fa fa-youtube mx-2" title="Watch on YouTube"></i>
                                                    <i class="fa fa-trash mx-2" title="Delete video"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Public</td>
                                    <td>no restriction</td>
                                    <td>upload date</td>
                                    <td>view counts</td>
                                    <td>comments counts</td>
                                    <td>66.7% liked</td>
                                </tr> -->


                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>            
        </div>

        <?php             
            include_once "components/footer.php";
        ?>

    </body>
</html>