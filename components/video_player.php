<div class="container play-container">
    <div class="row">
        <?php
            /*
                step 1: create database connection
                step 2: call info from database
                    videos, channels, views, users
                step 3: load to page
            */

            include_once "php_actions/db_helper.php";
            include_once "php_actions/utility.php";
            $db = new DBHelper(); // new instance(copy) of the class
            $get_videos = $db->set_query( "SQL_GET_ALL_VIDEOS", [] );
            $rows = $db->execute_query( $get_videos );
            $counter = 0;
            $video_player = '';
            $sidebar_right = '<div class="sidebar-right">';
            foreach ( $rows as $video ){
                $counter += 1;
                $videoId = $video['video_id'];
                $videoTitle = $video['video_title'];
                $videoDescription = $video['video_description'];
                $videoUrl = $video['video_url'];
                $videoTmbUrl = $video['video_thumbnail_url'];
                $videoDuration = $video['video_duration'];
                $videoCreatedAt = $video['video_created_at'];
                $videoUploaderId = $video['video_uploader_id_fk'];
                $videoUpdatedAt = $video['video_updated_at'];

                if( $videoUploaderId ){
                    $uploader_thumbnail = get_uploader_thumbnail($videoUploaderId, $db);
                    $channel_name = get_channel_name($videoUploaderId, $db);
                }                        
                if( $videoId ){
                    $viewCount = get_view_count($videoId, $db);
                    $viewCount = number_format($viewCount, 0); // 3,000,000
                } 
                if( $videoCreatedAt ){
                    $upload_interval = calc_upload_dt_interval($videoCreatedAt);
                } 

                
                // using video id, get teh count of all commments made for the video
                $commentSummary = '<div class="comment-summary">';
                if( $videoId ) {
                    $comments = get_video_comments($videoId, $db);                    
                    if ( count($comments) > 0 ){
                        $comments_total_count = strval(count($comments));
                        $commentSummary .= '<h4>'. $comments_total_count .' Comments</h4>';
                    }
                }
                $commentSummary .= '</div>';

                $existingCommentsDiv = '';
                if( $videoId ){                    
                    $comments = get_video_comments($videoId, $db);
                    if ( $comments ) {
                        // using user id, capture user profile and user name from users table
                        foreach ( $comments as $comm ){
                            $userId = $comm[0];
                            $comment = $comm[1];
                            $comment_date = $comm[2];
                            $comment_id = $comm[3];

                            // calculate date interval from today
                            $comm_interval = calc_upload_dt_interval($comment_date);

                            $user_info = get_uploader_info( $userId, $db );
                            $userProfileThumbnail = $user_info[0][4];
                            $userName = $user_info[0][1];

                            // get comment like and dislike counts
                            // using video_id_fk & comment_id_fk, get reaction (like, dislike) from - com_reactions
                            // count of likes, count of dislikes for each video comment.
                            $comment_reactions = get_comment_reactions( $videoId, $comment_id, $db );
                            $likes_count = strval($comment_reactions[0]); // convert int to str
                            $dislikes_count = strval($comment_reactions[1]);

                            // get replies for single comment
                            // using videoID and commentid, get userid and reply sentence
                            $comment_replies = get_comment_replies( $videoId, $comment_id, $db );
                            // for single comment!!!
                            // can have multiple replies
                            
                            if ( count($comment_replies) > 0 ) {
                                $view_replies = 'View '. strval( count($comment_replies) ) .' replies';
                            } else {
                                $view_replies = '';
                            }

                            $reply_div = '';
                            foreach( $comment_replies as $reply ){
                                $userId = $reply[0];
                                $reply_sentence = $reply[1];
                                $replys_date = $reply[2];
                                // using userid, get user name and profile
                                $rep_user_info = get_uploader_info($userId, $db);
                                $rep_user_Thumbnail = $rep_user_info[0][4];
                                $rep_user_Name = $rep_user_info[0][1];

                                // get date interval
                                $replys_date_interval = calc_upload_dt_interval($replys_date);


                                $reply_div .= '
                                <div class="col col-md-10 offset-md-2">                           
                                    <div class="existing-comment-replies row">
                                        <div class="col col-md-1">
                                            <img src="' . $rep_user_Thumbnail . '" alt="profile" >
                                        </div>
                                        <div class="col col-md-11">
                                            <h3>' . $rep_user_Name . ' <span>' . $replys_date_interval . '</span></h3>
                                            <p>' . $reply_sentence . '</p>
                                        </div>
                                    </div>
                                </div>
                                ';
                            }

                            $existingCommentsDiv .= '<div class="existing-comment row">';
                            
                            $existingCommentsDiv .= '<div class="col col-md-1">';
                            $existingCommentsDiv .= '<img src="' . $userProfileThumbnail . '" alt="profile" >';
                            $existingCommentsDiv .= '</div>';

                            $existingCommentsDiv .= '<div class="col col-md-11">';
                            $existingCommentsDiv .= '<h3>' . $userName . ' <span>' . $comm_interval . '</span></h3>';
                            $existingCommentsDiv .= '<p>' . $comment . '</p>';
                            $existingCommentsDiv .= '
                            <div class="existing-comment-reaction">
                                <i class="fa fa-thumbs-up" 
                                    onclick="comment_thumbs_up(event, this)"
                                    data-comment-id="'. $comment_id .'"
                                ></i> 
                                <span>' . $likes_count . '</span>

                                <i class="fa fa-thumbs-down"
                                    onclick="comment_thumbs_down(event, this)"
                                    data-comment-id="'. $comment_id .'"
                                ></i> 
                                <span>' . $dislikes_count . '</span>

                                <span>Reply</span>
                                <div>'. $view_replies .'</div>                                
                            </div>
                            ';
                            $existingCommentsDiv .= '</div>';

                            // add deplies to each comment
                            $existingCommentsDiv .= $reply_div;

                            
                            $existingCommentsDiv .= '</div>';
                        } 
                    }

                }      
                
                // get video like & dislike counts
                // from reactions table, if video id matches, count likes and dislikes
                if( $videoId ) {
                    $vid_reactions = get_video_reactions($videoId, $db);
                    $video_likes = strval($vid_reactions[0]);
                    $video_dislikes = strval($vid_reactions[1]);
                    // if count is more than a 1000, convert to 1k, 2k...
                    // for testing, set likes count to different value
                    // $vid_reactions[0] = 10123456; // test likes
                    // $vid_reactions[1] = 100456; // test dislikes
                    if( $vid_reactions[0] > 1000 ){
                        $num = round_to_nearest_thousand($vid_reactions[0]);
                        $video_likes = strval($num); // 1k
                    }
                    if( $vid_reactions[1] > 1000 ){
                        $num = round_to_nearest_thousand($vid_reactions[1]);
                        $video_dislikes = strval($num); // 1k
                    }
                    
                }

                // get video tags - video_tags
                if( $videoId ) {
                    $video_tags = get_video_tags( $videoId, $db );
                    if ($video_tags){
                        $videoTagsDiv = '<div class="tags">';
                        foreach($video_tags as $tag){
                            $videoTagsDiv .= '
                            <a href="?video_id='. $videoId .'&tag='. $tag .'"
                            class="btn btn-light btn-sm">
                            '. $tag .'
                            </a>';
                        }
                        $videoTagsDiv .= '</div>';                        
                    } else {
                        $videoTagsDiv = '<div class="tags"></div>';
                    }                    
                }

                // get logged in user avatar
                if( isset( $_SESSION['login_user_id'] ) ){
                    $profilePic = $_SESSION['login_user_profile_url'];
                } else {
                    $profilePic = './uploads/users/default-avatar.jpg';
                }



                // use the video id called from url
                if ( $urlVideoId == 0 ) { // not set in url
                    header('./index.php'); // redirects to home list
                } else if ( $urlVideoId == $counter ) {
                    // load the video on player
                    $video_player .= '
                    <div class="play-video">
                        <video controls autoplay>
                            <source src="' . $videoUrl . '" type="video/mp4" >
                        </video>

                        '. $videoTagsDiv .'

                        <div class="row">
                            <div class="col-6">
                                <h3>' . $videoTitle . '</h3>
                                <p>' . $viewCount . ' views &bull; ' . $upload_interval . '</p>
                            </div>

                            <div class="play-video-info col-6">                            
                                <div>
                                    <a href="#" class="text-decoration-none text-muted me-4">
                                        <i class="fa fa-thumbs-up" onclick="video_thumbs_up(event, this)"></i> 
                                        '. $video_likes .'
                                    </a>
                                    <a href="#" class="text-decoration-none text-muted me-4">
                                        <i class="fa fa-thumbs-down" onclick="video_thumbs_down(event, this)"></i> 
                                        '. $video_dislikes .'
                                    </a>
                                    <a href="#" class="text-decoration-none text-muted me-4">
                                        <i class="fa fa-share"></i>
                                        Share
                                    </a>
                                    <a href="#" class="text-decoration-none text-muted me-4">
                                        <i class="fa fa-bookmark"></i>
                                        Save
                                    </a>
                                    <a href="#" class="text-decoration-none text-muted">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                </div>
                            </div>
                        </div>


                        <hr>

                        <div class="owner">
                            <img src="' . $uploader_thumbnail . '" alt="profile" >
                            <div>
                                <p>' . $channel_name . '</p>
                                <span>19.4 subscribers</span>
                            </div>
                            <button type="button">Subscribe</button>
                        </div>

                        <div class="video-description">
                            <p>' . $videoDescription . '</p>
                            <p>Subscribe to - ' . $channel_name . '</p>

                            <hr>

                            '. $commentSummary .'

                            <div class="add-comment">
                                <img src="'. $profilePic .'" alt="profile" >
                                <input type="text" placeholder="Add a public comment"                                    
                                    id="add_public_comment" onclick="add_public_comment()"
                                >
                            </div>
                            ' . $existingCommentsDiv . '

                        </div>
                    </div>
                    ';
                } else {
                    // load the rest on sidebar right
                    // $sidebar_right .= '
                    // <div class="side-r-video-list border">
                    //     <a href="./?video_id=' . $videoId . '" class="thumbnail-small">
                    //         <img src="' . $videoTmbUrl . '" alt="side thumbnail" >
                    //     </a>
                    //     <div class="play-video-info">
                    //         <a href="./?video_id=' . $videoId . '">
                    //             ' . $videoTitle . '
                    //         </a>
                    //         <a href="./?channel_id=1" class="channel_name">' . $channel_name . '</a>
                    //         <p>' . $viewCount . ' views</p>
                    //     </div>

                    // </div>
                    // ';

                    $sidebar_right .= '
                    <div class="card mb-3 overflow-hidden" style="max-width: 370px; height: 120px;">
                        <div class="row g-0">
                            <div class="col-md-7">
                                <a href="./?video_id=' . $videoId . '">
                                    <img src="' . $videoTmbUrl . '"  class="img-fluid rounded-start" alt="video thumbnail" >
                                </a>
                            </div>
                            <div class="col-md-5">
                                <div class="card-body">
                                    <a href="./?video_id=' . $videoId . '"
                                        class="p card-title text-decoration-none text-truncate"
                                        style="max-width: 200px;"
                                    >
                                        ' . $videoTitle . '
                                    </a>
                                    <p class="card-text">
                                        <a href="./?channel_id=1" class="channel_name text-decoration-none">' . $channel_name . '</a><br>
                                        <small class="text-body-secondary">' . $viewCount . ' views</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
                }                     
            }
            $sidebar_right .= '</div>';
            echo $video_player;
            echo $sidebar_right;              
        ?>        
    </div>
</div>