<div class="container">
    <div class="video-list">

        <?php 
            /*
                step 1: create database connection
                step 2: call info from videos table
                step 3: load to page
            */
            include_once "php_actions/db_helper.php";
            include_once "php_actions/utility.php";
            $db = new DBHelper(); // new instance(copy) of the class
            $get_videos = $db->set_query( "SQL_GET_ALL_VIDEOS", [] );            
            $rows = $db->execute_query( $get_videos );

            // check if the url has search param
            $url = $_SERVER['REQUEST_URI'];
            $url_video_title = ''; // default
            $seach_term = false;
            if ( !is_null( parse_url($url, PHP_URL_QUERY) ) &&
            str_contains( parse_url($url, PHP_URL_QUERY), '=' ) ) {
                $urlArray = explode( '=', parse_url($url, PHP_URL_QUERY) );
                if( $urlArray[0] == 'search' ){ // if key is found
                    // %20 = empty space
                    $seach_term = true;
                    $url_video_title = preg_replace("/%20/", " ", $urlArray[1]);
                }
            }


            $localStorage_videoTitles = '';
            // check if any videos are found!!
            if ( count($rows) > 0 ){                
                foreach ( $rows as $video ){
                    $videoId = $video['video_id']; // 1, 2, 3
                    $videoTitle = $video['video_title']; // save to local storage
                    $videoDescription = $video['video_description'];
                    $videoUrl = $video['video_url'];
                    $videoTmbUrl = $video['video_thumbnail_url'];
                    $videoDuration = $video['video_duration'];
                    $videoCreatedAt = $video['video_created_at'];
                    $videoUploaderId = $video['video_uploader_id_fk'];
                    $videoUpdatedAt = $video['video_updated_at'];

                    if( $videoTitle ){
                        $localStorage_videoTitles .= $videoTitle . ','; // string
                    }

                    if( $videoCreatedAt ){
                        $upload_interval = calc_upload_dt_interval($videoCreatedAt);
                    }
                    if( $videoUploaderId ){
                        $uploader_thumbnail = get_uploader_thumbnail($videoUploaderId, $db);
                        $channel_name = get_channel_name($videoUploaderId, $db);
                    }
                    if( $videoId ){
                        $viewCount = get_view_count($videoId, $db);
                        $viewCount = number_format($viewCount, 0); // 3,000,000
                    }

                    // step 3: load to page

                    // check the search term is set
                    if( $seach_term ){
                        // only load the video with matching title
                        if( $videoTitle == $url_video_title ){
                            echo '
                                <div class="video">
                                    <a href="./?video_id=' . $videoId . '">
                                        <img src="' . $videoTmbUrl . '" class="thumbnail" alt="link to thumbnail" >
                                    </a>
                                    <div class="flex-div">
                                        <a href="./?channel_id=1" >
                                            <img src="' . $uploader_thumbnail . '" class="thumbnail" alt="uploader profile" >
                                        </a>
                                        
                                        <div class="video-info">
                                            <a 
                                                href="./?video_id=' . $videoId . '"
                                                title="' . $videoTitle . '"
                                            >' . $videoTitle . '
                                            </a>
                                            <a href="./?channel_id=1" class="channel_name">' . $channel_name . '</a>
                                            <p>' . $viewCount . ' views • ' . $upload_interval . '</p>
                                        </div>
                                    </div>
                                </div>
                            '; 
                        }
                    } else {
                        // load all videos
                        echo '
                            <div class="video">
                                <a href="./?video_id=' . $videoId . '">
                                    <img src="' . $videoTmbUrl . '" class="thumbnail" alt="link to thumbnail" >
                                </a>
                                <div class="flex-div">
                                    <a href="./?channel_id=1" >
                                        <img src="' . $uploader_thumbnail . '" class="thumbnail" alt="uploader profile" >
                                    </a>
                                    
                                    <div class="video-info">
                                        <a 
                                            href="./?video_id=' . $videoId . '"
                                            title="' . $videoTitle . '"
                                        >' . $videoTitle . '
                                        </a>
                                        <a href="./?channel_id=1" class="channel_name">' . $channel_name . '</a>
                                        <p>' . $viewCount . ' views • ' . $upload_interval . '</p>
                                    </div>
                                </div>
                            </div>
                        '; 
                    }
                    
                    
                }
            } else {
                echo '
                    <div class="alert alert-warning text-center display-6 py-5 my-5" role="alert">
                        There are no recommended videos at this time! Sorry :)
                    </div>
                ';
            }

            // save to localstorage
            echo '
            <script>
                localStorage.setItem(
                    "video_titles", 
                    "'. $localStorage_videoTitles .'"
                );
            </script>
            ';
        ?>
    </div>
</div>