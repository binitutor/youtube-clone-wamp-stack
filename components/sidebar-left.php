<?php
    // studio key must exist even when its value is false
    if( $_SESSION['studio'] == true ) {
        // show studio sidebar
        echo '
        <div class="sidebar">
            <div class="shortcut-links">
                <a href="#">
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#">
                    <i class="fa fa-caret-square-o-right"></i>
                    <span>Content</span>
                </a>
                <a href="#">
                    <i class="fa fa-file-movie-o"></i>
                    <span>Analytics</span>
                </a>
                <hr>        
                <a href="#">
                    <i class="fa fa-file-audio-o"></i>
                    <span>Audio Library</span>
                </a>   
                <a href="#">
                    <i class="fa fa-money"></i>
                    <span>Earn</span>
                </a>   
                <a href="#">
                    <i class="fa fa-gear"></i>
                    <span>Settings</span>
                </a>   
                <a href="#">
                    <i class="fa fa-chevron-down"></i>
                    <span>Show more</span>
                </a>
            </div>
        </div>
        ';
    } else {
        // show public sidebar
        echo '
        <div class="sidebar">
            <div class="shortcut-links">
                <a href="#">
                    <i class="fa fa-home"></i>
                    <span>Home</span>
                </a>
                <a href="#">
                    <i class="fa fa-caret-square-o-right"></i>
                    <span>Shorts</span>
                </a>
                <a href="#">
                    <i class="fa fa-file-movie-o"></i>
                    <span>Subscriptions</span>
                </a>
                <hr>        
                <a href="#">
                    <i class="fa fa-th-large"></i>
                    <span>Library</span>
                </a>   
                <a href="#">
                    <i class="fa fa-history"></i>
                    <span>History</span>
                </a>   
                <a href="#">
                    <i class="fa fa-list"></i>
                    <span>Playlist</span>
                </a>   
                <a href="#">
                    <i class="fa fa-chevron-down"></i>
                    <span>Show more</span>
                </a>
                <hr> 
            </div>
            <div class="subscribed-list">
                <h3>SUBSCRIPTIONS</h3>
                <a href="#">
                    <img src="./assets/img/jack.png" >
                    <span>John Doe &bull;</span>
                </a>
                <a href="#">
                    <img src="./assets/img/megan.png" >
                    <span>Jane Doe &bull;</span>
                </a>
                <a href="#">
                    <img src="./assets/img/simon.png" >
                    <span>Peter McDonalds &bull;</span>
                </a>
                <a href="#">
                    <img src="./assets/img/tom.png" >
                    <span>Tom Aberson &bull;</span>
                </a>
            </div>
        </div>
        ';
    }
?>
