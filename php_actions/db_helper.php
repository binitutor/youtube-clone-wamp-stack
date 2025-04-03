<?php

/**** DATABASE *****/

class DBHelper {
    private $conn;

    public function __construct(){
        // save environment vars
        $this->save_env_vars();
        
        // load them to the variables below        
        $servername = getenv('DB_HOST');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');
        $database = getenv('DB_NAME');

        // use them to connect to database
        $this->conn = new mysqli($servername, $username, $password, $database);
        // check connection
        if ( $this->conn->connect_error ){
            die("Connection failed: " . $this->conn->connect_error);
        }        
    }

    public function set_query( $query_id, $query_params ){ 
        // associative array - key->val
        $YT_DB_QUERIES = array(
            "SQL_GET_ALL_VIDEOS"=>"SELECT * FROM videos",
            "SQL_GET_ALL_REACTIONS"=>"SELECT * FROM reactions",
            "SQL_GET_ALL_COMMENT_REACTIONS"=>"SELECT * FROM com_reactions",
        );
        // update query
        if ( count($query_params) > 0 ){
            foreach( $query_params as $param ){
                if ( count($query_params) == 1 ){
                    $query = "SELECT * FROM users WHERE user_id = $param";
                    $YT_DB_QUERIES["SQL_GET_USER_BY_ID"] = $query;

                    $query = "SELECT view_count FROM views WHERE video_id_fk = $param";
                    $YT_DB_QUERIES["SQL_GET_VIEWS_BY_VID_ID"] = $query;

                    $query = "SELECT * FROM channels WHERE channel_owner_fk = $param";
                    $YT_DB_QUERIES["SQL_GET_CHANNEL_BY_USER_ID"] = $query;
                    
                    $query = "SELECT * FROM comments WHERE video_id_fk = $param";
                    $YT_DB_QUERIES["SQL_GET_COMMENTS_BY_VIDEO_ID"] = $query;
                    
                    $query = "SELECT * FROM reactions WHERE video_id_fk = $param";
                    $YT_DB_QUERIES["SQL_GET_VID_REACTIONS_BY_VIDEO_ID"] = $query;
                    
                    $query = "SELECT video_tags FROM videos WHERE video_id = $param";
                    $YT_DB_QUERIES["SQL_GET_TAGS_BY_VIDEO_ID"] = $query;
                    
                    $query = "SELECT * FROM videos WHERE channel_id_fk = $param";
                    $YT_DB_QUERIES["SQL_GET_ALL_CHANNEL_VIDEOS"] = $query;
                    
                    $query = "SELECT * FROM views WHERE video_id_fk = $param";
                    $YT_DB_QUERIES["SQL_GET_VIEWS_BY_VIDEO_ID"] = $query;
                    
                    $query = "INSERT INTO views (view_count, video_id_fk) VALUES (0, '$param') ";
                    $YT_DB_QUERIES["SQL_INITIALIZE_VIEWS_FOR_VIDEO_ID"] = $query;                 
                    
                    $query = "SELECT * FROM videos WHERE video_id = $param";
                    $YT_DB_QUERIES["SQL_GET_VIDEO_BY_ID"] = $query;

                }
                else if ( count($query_params) == 2 ) {
                    $video_id = $query_params[0];
                    $comment_id = $query_params[1];
                    $query = "SELECT reaction FROM com_reactions WHERE video_id_fk = $video_id AND comment_id_fk = $comment_id";
                    $YT_DB_QUERIES["SQL_GET_COMM_REACTIONS_BY_VIDEO_ID_AND_COMMENT_ID"] = $query;
                
                    $query = "SELECT user_id_fk, reply, replys_date FROM replys WHERE video_id_fk = $video_id AND comment_id_fk = $comment_id";
                    $YT_DB_QUERIES["SQL_GET_COMM_REPLIES_BY_VIDEO_ID_AND_COMMENT_ID"] = $query; 
                }
                else if ( count($query_params) == 3 ) {
                    $video_id = $query_params[0];
                    $userId = $query_params[1];
                    $comnt_or_rect = $query_params[2]; // comment or reaction
                    // mysqli wants values to be enclosed in single quotes!!
                    $query = "INSERT INTO comments (video_id_fk, user_id_fk, comment_text, comment_date) VALUES ('$video_id', '$userId', '$comnt_or_rect', CURRENT_TIMESTAMP )";
                    $YT_DB_QUERIES["SQL_SET_COMMENT"] = $query;  
                    
                    $query = "INSERT INTO reactions (video_id_fk, user_id_fk, reaction) VALUES ('$video_id', '$userId', '$comnt_or_rect' )";
                    $YT_DB_QUERIES["SQL_SET_REACTION"] = $query;
                    
                    $query = "UPDATE reactions SET reaction = '$comnt_or_rect' WHERE video_id_fk = '$video_id' AND user_id_fk = '$userId' ";
                    $YT_DB_QUERIES["SQL_UPDATE_REACTION"] = $query;
                    
                    
                } else if( count($query_params) == 4 ){
                    $video_id = $query_params[0];
                    $userId = $query_params[1];
                    $commentId = $query_params[2];
                    $comnt_or_rect = $query_params[3]; // comment or reaction
                    $query = "UPDATE com_reactions SET reaction = '$comnt_or_rect' WHERE video_id_fk = '$video_id' AND user_id_fk = '$userId' AND comment_id_fk = '$commentId' ";
                    $YT_DB_QUERIES["SQL_UPDATE_COMM_REACTION"] = $query; 

                    $query = "INSERT INTO com_reactions (video_id_fk, user_id_fk, comment_id_fk, reaction) VALUES ('$video_id', '$userId', '$commentId', '$comnt_or_rect' )";
                    $YT_DB_QUERIES["SQL_SET_COMM_REACTION"] = $query; 
                                        
                    $videoId = $query_params[0];
                    $videoTitle = $query_params[1];
                    $videoDescription = $query_params[2];
                    $videoThumbnailUrl = $query_params[3];
                    
                    $query = "UPDATE videos SET video_title = '$videoTitle', video_description = '$videoDescription', video_thumbnail_url = '$videoThumbnailUrl' WHERE video_id = '$videoId' ";
                    $YT_DB_QUERIES["SQL_UPDATE_VIDEO_BY_ID"] = $query;

                    $channelName = $query_params[0];
                    $channelDescription = $query_params[1];
                    $channelOwnerId = $query_params[2];
                    $channelCreatedDate = $query_params[3];
                    $query = "INSERT INTO channels (channel_name, channel_description, channel_created_at, channel_owner_fk) VALUES ('$channelName', '$channelDescription', '$channelCreatedDate', '$channelOwnerId' )";
                    $YT_DB_QUERIES["SQL_CREATE_CHANNEL"] = $query; 
                       

                }  else {
                    $videoTitle = $query_params[0];
                    $videoDescription = $query_params[1];
                    $videoURL = $query_params[2];
                    $thumbnailURL = $query_params[3]; 
                    $videoDuration = $query_params[4]; 
                    $uploadDate = $query_params[5];
                    $userId = $query_params[6];
                    $channelId = $query_params[7];                   
                    
                    $query = "INSERT INTO videos (video_title, video_description, video_url, video_thumbnail_url, video_duration, video_created_at, video_uploader_id_fk, channel_id_fk) VALUES ( '$videoTitle', '$videoDescription', '$videoURL', '$thumbnailURL', '$videoDuration', '$uploadDate', '$userId', '$channelId' )";
                    $YT_DB_QUERIES["SQL_UPLOAD_VIDEO"] = $query; 
                    
                }              
            }
        }

        // return value
        if ( $query_id ){
            return $YT_DB_QUERIES[$query_id];
        } else {
            return null;
        }
    }

    public function execute_query( $query ){
        $result = $this->conn->query($query);
        if ( is_bool($result) ) { // t/f
            return $result;
        } else { // rows found
            if ( $result->num_rows > 0 ) {
                return $result->fetch_all(MYSQLI_ASSOC);
            } else { return []; }
        }
    }

    private function save_env_vars(){
        // read environment variables
        $env = file_get_contents(__DIR__ . "/../.env"); // one string
        $env_array = explode("\n", $env); // 4 string values
        // save values to php environment
        foreach ( $env_array as $val ){
            preg_match("/([^#]+)\=(.*)/", $val, $matches); // checks allows characters
            if ( isset( $matches[2] ) ){
                putenv( trim($val) ); // saves 4 items to environment
            }
        }
    }


}









