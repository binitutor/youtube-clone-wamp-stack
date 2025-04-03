<?php
    
    // checking the url before loading page
    $url = $_SERVER['REQUEST_URI'];
    $show_video_player = false; // by default, list all videos.
    $urlVideoId = 0; // default
    // if video id is passed as url param, load video player
    if ( !is_null( parse_url($url, PHP_URL_QUERY) ) &&
        str_contains( parse_url($url, PHP_URL_QUERY), '=' ) ) {
        $urlArray = explode( '=', parse_url($url, PHP_URL_QUERY) ); // [arg=val, arg2=val2 ...]
        if( $urlArray[0] == 'video_id' ){ // if key is found
            $show_video_player = true;
            $urlVideoId = (int)$urlArray[1]; // capture the video id
        }
    }

    // load content (video list or video player)
    if ( $show_video_player ){ // if true
        // load video player
        include_once "components/video_player.php";
    } else {
        // list videos
        include_once "components/video_list.php";
    }

    // $s = ;
    // $path = $s['path']; // root bath
    // $query = $s['query']; // query string, comes after ?
    // $video_id = explode('=', $s['query'])[1]; // capture second element index
    


?>


