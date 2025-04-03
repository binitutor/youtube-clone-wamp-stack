let menuIcon = document.querySelector(".menu-icon");
let sidebar = document.querySelector(".sidebar");
let container = document.querySelector(".container");

if (menuIcon) {
    menuIcon.onclick = function () {
        // toggle - On/Off
        sidebar.classList.toggle("small-sidebar");
        container.classList.toggle("large-container");
    }
}


function reload_home() {
    location.href = "./index.php";
}

function view_password() {
    var pwInput = document.getElementById('password');
    var pwInputType = pwInput.getAttribute('type')
    if (pwInputType == 'password') { // reveal it
        pwInput.removeAttribute('type')
        pwInput.setAttribute('type', 'text')
    } else { // hide it
        pwInput.removeAttribute('type')
        pwInput.setAttribute('type', 'password')
    }
}

// AUTHENTICATION
function validateLoginForm(e) {
    e.preventDefault(); // stops the submission process

    // capture form values
    var userEmail = document.getElementById('email').value;
    var userPw = document.getElementById('password').value;

    // console.log(
    //     `Email: ${userEmail}
    //     Password: ${userPw}
    //     `
    // )

    // run validation
    /*
        - ensure the email field has the proper format abc@abc.com
        - ensure the password is certain length, allow and stop certain characters

    */

    // login
    login(userEmail, userPw);
}

function login(email, password) {
    // make ajax request to backend server
    // pass the values for authentication
    var xmlhttp = new XMLHttpRequest();
    var formData = new FormData();
    formData.append('em', email)
    formData.append('pw', password)
    formData.append('action', 'login')
    xmlhttp.onreadystatechange = function () {
        // monitor the server response
        if (this.readyState == 4 && this.status == 200) {
            // console.log('authentication was successful!')
            // console.log(this.responseText)

            // convert response from string to json object
            var respJ = JSON.parse(this.responseText);

            // create alert box
            var respAlert = alertNotification(
                respJ.alertClass, respJ.message, respJ.name
            );

            document.getElementById('msg').innerHTML = respAlert.outerHTML;

            // run loading animation for 3 seconds
            loadingAnimation();

            // redirect to home page after 3 seconds !!!
            setTimeout(function () { location.href = './index.php'; }, 3000);

        } else {
            console.log('Authenticating...')
        }
    }
    var pageUrl = './php_actions/authenticate.php'
    xmlhttp.open('POST', pageUrl, true) // create post reqest
    xmlhttp.send(formData) // send values to server

}

function logout() {
    // make ajax request to backend server
    // pass the values for authentication
    var xmlhttp = new XMLHttpRequest();
    var formData = new FormData();
    formData.append('action', 'logout')
    xmlhttp.onreadystatechange = function () {
        // monitor the server response
        if (this.readyState == 4 && this.status == 200) {
            console.log('logged out successfully!')
            var alertClass = JSON.parse(this.responseText).alertClass
            var message = JSON.parse(this.responseText).message
            var respAlert = alertNotification(
                alertClass, message, ''
            );
            // document.getElementById('msg').innerHTML = respAlert.outerHTML;

            // run loading animation for 3 seconds
            loadingAnimation();

            // redirect to home page after 3 seconds !!!
            setTimeout(
                function () {
                    // redirect to home
                    location.href = './index.php';
                },
                3000
            );

        }
    }
    var pageUrl = './php_actions/authenticate.php'
    xmlhttp.open('POST', pageUrl, true) // create post reqest
    xmlhttp.send(formData) // send values to server
}

function alertNotification(alertClass, message, name) {
    // create alert box
    var respAlert = document.createElement('div')
    respAlert.setAttribute('class', alertClass) // red or green
    respAlert.setAttribute('role', 'alert')
    respAlert.textContent = message + "\n" + name

    return respAlert
}

function loadingAnimation() {
    // opens bootstrap modal for 3 seconds
    // closes the boostrap modal after 3 seconds
    // toggle on and off
    if ($('#loadingModal').is(':hidden')) {
        $('#loadingModal').modal('toggle')
    }
    // link jquery library first
}

function close_login_prompt() {
    // dismiss notification
    document.getElementById('login_prompt').style.display = 'none';
}

function close_search_result() {
    // dismiss notification
    document.getElementById('search_result').style.display = 'none';
}

// REGISTRATION
function validateRegisterForm(e) {
    e.preventDefault(); // stops the submission process

    // capture form values  
    var userName = document.getElementById('full_name').value;
    var userEmail = document.getElementById('email').value;
    var userPw = document.getElementById('password').value;
    var confirmUserPw = document.getElementById('c_password').value;
    var userProfile = document.getElementById('user_profile').files[0];

    if (userPw === confirmUserPw) {
        // accept it!
        // console.log(
        //     `username: ${userName}
        //     Email: ${userEmail}
        //     Password: ${userPw}
        //     Confirm Password: ${confirmUserPw}
        //     user Profile: ${userProfile}
        //     `
        // )         
        register(userName, userEmail, userPw, userProfile);

    } else {
        // show warning - password Mismatch!
        // create alert box
        var respAlert = alertNotification(
            'alert alert-danger', 'Password Mismatch!', ''
        );
        document.getElementById('msg').innerHTML = respAlert.outerHTML;
    }

}

function register(username, email, password, profile) {
    // make ajax request to backend server
    // pass the values for authentication
    var xmlhttp = new XMLHttpRequest();
    var formData = new FormData();
    formData.append('full_name', username)
    formData.append('email', email)
    formData.append('password', password)
    formData.append('profile', profile)
    formData.append('action', 'register')
    xmlhttp.onreadystatechange = function () {
        // monitor the server response
        if (this.readyState == 4 && this.status == 200) {
            // console.log('Registration was successful!')
            // console.log(this.responseText)
            // convert response from string to json object
            var respJ = JSON.parse(this.responseText);
            // create alert box
            var respAlert = alertNotification(
                respJ.alertClass, respJ.message, respJ.name
            );
            document.getElementById('msg').innerHTML = respAlert.outerHTML;
            // run loading animation for 3 seconds
            loadingAnimation();
            // redirect to home page after 3 seconds !!!
            setTimeout(function () { location.href = './index.php'; }, 3000);

        } else {
            console.log('Registering...')
        }
    }
    var pageUrl = './php_actions/authenticate.php'
    xmlhttp.open('POST', pageUrl, true) // create post reqest
    xmlhttp.send(formData) // send values to server

}



function add_public_comment(e) {
    // console.log('input field clicked')
    let add_public_comment_field = document.getElementById('add_public_comment');
    add_public_comment_field.addEventListener(
        'keydown', // keyboard key press event
        function (event) {
            // console.log(event.key); // it records every key entry
            // check if the user is logged in.
            var auth = check_user_login();
            var authStatus = auth[0]; // t/f
            var authResponse = auth[1]; // array            
            // console.log(auth)
            // console.log(authStatus)
            // console.log(authResponse)

            if (authStatus) { // if true = logged in 
                // capture these values only if the user is logged in!!!
                var login_user_id = authResponse[0];
                var url_video_id = authResponse[4];
                // user is logged in. user can write and submit the new comment.
                if (event.key === 'Enter') {
                    // capture the text
                    var text = add_public_comment_field.value;
                    console.log('New comment: ', text);
                    // attempt to save the new comment to database
                    // ajax http request to the backend server
                    submitComment(text, login_user_id, url_video_id)
                }
            } else { // if false
                // user is not logged in. if not logged in, enable the prompt.
                login_prompt(
                    'keydown',
                    'To add a comment, please login.',
                    add_public_comment_field
                );
            }
        }
    );
}

function submitComment(commentText, login_user_id, url_video_id) {
    var formData = new FormData();
    formData.append('video_id', url_video_id)
    formData.append('user_id', login_user_id)
    formData.append('comment', commentText)
    var pageUrl = './php_actions/add_comment.php'
    backendRequest(pageUrl, formData);

    // var videoPageUrl = location.href;
    // // make ajax request to backend server
    // // video id, user id, comment
    // var xmlhttp = new XMLHttpRequest();

    // console.log(
    //     `
    //     url_video_id ${url_video_id}
    //     login_user_id ${login_user_id}
    //     commentText ${commentText}
    //     videoPageUrl ${videoPageUrl}
    //     `
    // )
    // xmlhttp.onreadystatechange = function () {
    //     // monitor the server response
    //     if (this.readyState == 4 && this.status == 200) {
    //         // console.log('authentication was successful!')
    //         // console.log(this.responseText)    
    //         // document.getElementById('test').innerHTML =   this.responseText      

    //         // run loading animation for 3 seconds
    //         loadingAnimation();

    //         // redirect to home page after 3 seconds !!!
    //         setTimeout(function () { location.href = videoPageUrl; }, 3000);

    //     }
    // }
    // 
    // xmlhttp.open('POST', pageUrl, true) // create post reqest
    // xmlhttp.send(formData) // send values to server
}

function video_thumbs_up(event, thisParent) {
    // check if user is logged in
    var auth = check_user_login();
    var authStatus = auth[0]; // t/f
    var authResponse = auth[1]; // array    
    if (authStatus) {
        // user is logged in 
        var login_user_id = authResponse[0];
        var url_video_id = authResponse[4];
        // submit the video like event to database
        var formData = new FormData();
        formData.append('video_id', url_video_id)
        formData.append('user_id', login_user_id)
        formData.append('reaction', 'like')
        var pageUrl = './php_actions/add_reaction.php'
        backendRequest(pageUrl, formData);
    } else {
        // if not in, show prompt box
        login_prompt(
            event,
            'To add your reaction, please login.',
            thisParent
        );
    }
}

function video_thumbs_down(event, thisParent) {
    // check if user is logged in
    var auth = check_user_login();
    var authStatus = auth[0]; // t/f
    var authResponse = auth[1]; // array    
    if (authStatus) {
        // user is logged in 
        var login_user_id = authResponse[0];
        var url_video_id = authResponse[4];
        // submit the video like event to database
        var formData = new FormData();
        formData.append('video_id', url_video_id)
        formData.append('user_id', login_user_id)
        formData.append('reaction', 'dislike')
        var pageUrl = './php_actions/add_reaction.php'
        backendRequest(pageUrl, formData);
    } else {
        // if not in, show prompt box
        login_prompt(
            event,
            'To add your reaction, please login.',
            thisParent
        );
    }
}

function comment_thumbs_up(event, thisParent) {
    // check if user is logged in
    var auth = check_user_login();
    var authStatus = auth[0]; // t/f
    var authResponse = auth[1]; // array    
    if (authStatus) {
        // user is logged in 
        var login_user_id = authResponse[0];
        var url_video_id = authResponse[4];
        // get data attribute
        var comment_id = thisParent.getAttribute('data-comment-id');
        // submit the video like event to database
        var formData = new FormData();
        formData.append('video_id', url_video_id)
        formData.append('user_id', login_user_id)
        formData.append('comment_id', comment_id)
        formData.append('reaction', 'like')
        var pageUrl = './php_actions/add_comm_reaction.php'
        backendRequest(pageUrl, formData);
    } else {
        // if not in, show prompt box
        login_prompt(
            event,
            'To add your reaction, please login.',
            thisParent
        );
    }
}

function comment_thumbs_down(event, thisParent) {
    // check if user is logged in
    var auth = check_user_login();
    var authStatus = auth[0]; // t/f
    var authResponse = auth[1]; // array    
    if (authStatus) {
        // user is logged in 
        var login_user_id = authResponse[0];
        var url_video_id = authResponse[4];
        // get data attribute
        var comment_id = thisParent.getAttribute('data-comment-id');
        // submit the video like event to database
        var formData = new FormData();
        formData.append('video_id', url_video_id)
        formData.append('user_id', login_user_id)
        formData.append('comment_id', comment_id)
        formData.append('reaction', 'dislike')
        var pageUrl = './php_actions/add_comm_reaction.php'
        backendRequest(pageUrl, formData);
    } else {
        // if not in, show prompt box
        login_prompt(
            event,
            'To add your reaction, please login.',
            thisParent
        );
    }
}

function login_prompt(eventType, messageText, inputField) {
    var promptBox = document.getElementById('login_prompt'); // output div
    promptBox.style.display = 'block'; // show the box
    // add the message
    document.getElementById('login_prompt_msg').innerText = messageText;

    if (eventType == 'keydown') {
        // key press event uses keyboard, not a mouse.
        // thus, doesnt have x and y coordinates.
        // use bounding cleiekt recatangle to find the position
        var box = inputField.getBoundingClientRect();
        var top = box.top;
        var bottom = box.bottom;
        var left = box.left;
        var right = box.right;
        // calculate x and y coordinates for the click thumbs up button
        var xc = left + 'px';
        var yc = (top + 50) + 'px'; // lower it a bit than the comment field
        // console.log(xc, yc)
        // position is close to the button
        promptBox.style.top = yc; // y-axis
        promptBox.style.left = xc; // x-axis 
    } else {
        promptBox.style.display = 'block'; // show the box
        // calculate x and y coordinates for the click thumbs up button
        var xc = eventType.clientX + 'px'; // 812
        var yc = eventType.clientY + 'px';
        // console.log(xc, yc)
        // position is close to the button
        promptBox.style.top = yc; // y-axis
        promptBox.style.left = xc; // x-axis
    }

}

function check_user_login() {
    var authStatus = false;
    var login_user_id = document.getElementById('login_user_id').textContent
    var login_user_name = document.getElementById('login_user_name').textContent
    var login_user_email = document.getElementById('login_user_email').textContent
    var login_user_profile_url = document.getElementById('login_user_profile_url').textContent
    var url_video_id = document.getElementById('url_video_id').textContent;
    var authResponse = [];
    if (login_user_name == null || login_user_name == '') {
        return [authStatus, authResponse];
    } else { // logged in~
        authStatus = true;
        authResponse.push(
            login_user_id, login_user_name, login_user_email,
            login_user_profile_url, url_video_id
        );
        return [authStatus, authResponse]
    }
}

function backendRequest(
    reqURL, formData, retData = false, tempAction = false, channelAction = false
) {
    // for( const [key, val] of formData){
    //     console.log(`key-${key}, val-${val}`);
    // }
    var videoPageUrl = location.href; // current page = video player
    // make ajax request to backend server
    // video id, user id, comment
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        // monitor the server response
        if (this.readyState == 4 && this.status == 200) {
            if (retData) { // return the response to the caller
                // console.log(this.responseText);
                let videoData = JSON.parse(this.responseText);
                // output to editor modal 
                var videoId = videoData['video_id'];
                var videoTitle = videoData['video_title'];
                var videoDescription = videoData['video_description'];
                var videoThumbnailUrl = videoData['video_thumbnail_url'];
                var videoTags = videoData['video_tags'];
                // var editorModal = document.getElementById('videoEditorModal');
                var editorVideoId = document.getElementById('video_id');
                editorVideoId.value = videoId;
                var editorModalTitle = document.getElementById('edit_videoTitle');
                editorModalTitle.value = videoTitle;
                var editorModalDesc = document.getElementById('edit_videoDescription');
                editorModalDesc.value = videoDescription;
                var editorModalThumbnail = document.getElementById('edit_videoThumbnail');
                editorModalThumbnail.src = videoThumbnailUrl;

                // 
                // {
                //     "video_id":"1",
                //     "video_title":"Overview of the Entire Bible in 17 Minutes!",
                //     "video_description":"The Bible is comprised of 66 different books written by about 40 authors over a period of about 1,500 years. It has nearly 1,200 chapters and over 30,000 verses. If you were to read a chapter each day, it would take over 3 years to read the entire Bible!",
                //     "video_url":".\/uploads\/videos\/bible.mp4",
                //     "video_thumbnail_url":".\/uploads\/thumbnails\/tmb-bible.png",
                //     "video_created_at":"2025-03-24",
                //     "video_uploader":"1000",
                //     "video_tags":"Bible,God",
                //     "channel_id":"1"
                // }




            } else if (tempAction) {
                // do nothing...
                // console.log('temp file uploaded!!!')
                // console.log(this.responseText)
                var tempThumbnailInfo = JSON.parse(this.responseText);
                // set the thumbnail image src path to relative path to tmp folder
                document.getElementById('edit_videoThumbnail').src = tempThumbnailInfo.relPath;
                // set the thumbnail file name
                document.getElementById('tmb_name').innerText = tempThumbnailInfo.filename;


            } else if (channelAction) {
                // var tempThumbnailInfo = JSON.parse(this.responseText);
                var channelId = this.responseText;
                // console.log(channelId)
                // update hidden channel info
                document.getElementById('channel_id').value = channelId;
            } else { // reload page
                // console.log('authentication was successful!')
                // console.log(this.responseText)    
                // document.getElementById('test').innerHTML =   this.responseText      

                // run loading animation for 3 seconds
                loadingAnimation();

                // redirect to home page after 3 seconds !!!
                setTimeout(function () { location.href = videoPageUrl; }, 3000);
            }
        }
    }
    xmlhttp.open('POST', reqURL, true) // create post reqest
    xmlhttp.send(formData) // send values to server
}


/*** SEARCH AND FILTER ***/

// we need event listener, part on nav bar
document.getElementById('search-word').addEventListener(
    'input', function (event) {
        let searchWord = event.target.value; // updated with every keystrock        
        // when user types a word, start searching for video title
        // hide the videos that do not have the word in them
        // we dont want to make database call every time user types a single letter.
        // we can collect all video titles in localstorag and reference them from browser.

        // as soon as search word is typed, display the search box
        let recomendationBox = document.getElementById('search_result');
        let recomendationResults = document.getElementById('search_result_msg');

        if (searchWord) {
            // recomendationBox.style.display = 'solid'; // display box
            recomendationBox.removeAttribute('style');
            // refresh the box
            recomendationResults.innerHTML = '';
        }


        // when videos are loaded the first time, save the titles into localstorage.
        // get stored data
        let storedTitles_str = localStorage.getItem("video_titles");
        let storedTitles = storedTitles_str.split(','); // array
        // remove empty string elements from array
        let filteredArray = [];
        storedTitles.forEach(item => {
            if (item !== "") {
                filteredArray.push(item);
            }
        });
        // console.log(filteredArray);
        // console.log(typeof filteredArray);
        let searchResults = [];
        filteredArray.forEach(title => {
            // search the word or letter in the list of titles
            if (title.toLowerCase().includes(searchWord.toLowerCase())) {
                // this is a valid title
                searchResults.push(title);
                // create title link
                var titleLink = document.createElement('p');
                titleLink.setAttribute('onclick', "update_search_field('" + title + "')");
                titleLink.setAttribute('class', "text-decoration-none text-mute");
                titleLink.innerText = title;
                // display to box
                recomendationResults.appendChild(titleLink);
            }
        });

        // console.log('Search Word: ', searchWord)
        // console.log('Search Results: ', searchResults)

        // if enter key is pressed or 
        // search icon is clicked or 
        //      update url -- update_search_field()
        // console.log('key - ', event)

    }
);

function update_search_field(title) {
    // set the title to search input field
    document.getElementById('search-word').value = title;
    // close the word suggestion box window
    document.getElementById('search_result').style.display = 'none';
}

function search_video_title() {
    let searchWord = document.getElementById('search-word').value;
    if (searchWord !== '') {
        location.href = './index.php?search=' + searchWord; // browse and pass param
        // /index.php?search=Little%20River%20Falls
        // %20 = empty space
        // on video list loader, make sure this filter is applied
    }
}

/*** STUDIO SCRIPT ***/
function edit_video(event, videoID) {
    // once the modal is loaded, add the video information to it
    // get video info using the ID
    var formData = new FormData();
    formData.append('video_id', videoID)
    formData.append('video_action', 'get')
    var pageUrl = './php_actions/edit_video.php'
    // we expect data to be sent back
    backendRequest(pageUrl, formData, true);
}

function upload_tmb_file() {
    // dynamically trigger the file input field click event
    document.getElementById('edit_videoThumbnail_input').click();
}

function upload_video_tmb_file() {
    // dynamically trigger the file input field click event
    document.getElementById('upload_videoThumbnail').click();
}

function upload_video_file() {
    // dynamically trigger the file input field click event
    document.getElementById('upload_videoInput').click();
}

function open_video_scheduler() {
    // open scheduler only if the visibilty is turned off
    var visibility = document.getElementById('videoVisibility');
    if (visibility.checked) {
        // dont show scheduler calendar
        document.getElementById('videoSchedule_div').style.display = 'none';
    } else {
        // show
        document.getElementById('videoSchedule_div').style.display = 'block';
    }
}

function set_video_thumbnail(e) {

    const videoThumbnailFile = e.target.files[0]; // capture input
    if (videoThumbnailFile) { // if file is set!
        // set thumbnail preview
        const videoThumbnailUrl = URL.createObjectURL(videoThumbnailFile);
        document.getElementById('preview_upload_videoThumbnail').src = videoThumbnailUrl;
        let videoThumbnailFileName = videoThumbnailFile.name;
        document.getElementById('upload_tmb_name').innerText = videoThumbnailFileName;

        // hide upload button, reveal change button
        document.getElementById('upload_videoThumbnail_fa').style.display = 'none';
        document.getElementById('change_videoThumbnail_fa').style.display = 'block';
        // reveal the preview
        document.getElementById('preview_upload_videoThumbnail').style.display = 'block';

    }

}

function reveal_hidden_content(e) {
    const videoFile = e.target.files[0]; // capture input
    if (videoFile) { // if file is set!
        // reveal metadata inputs
        document.getElementById('hidden-upload-video').style.display = 'block';
        // hide video upload field 
        document.getElementById('upload-video').style.display = 'none';
        // animate the upload process
        animateProgressBar();

        // display uploaded video info
        // var upload_videoInput = document.getElementById('upload_videoInput');
        // create video url
        const videoUrl = URL.createObjectURL(videoFile);
        // we can set a quick replay preview (optional)
        let videoFileName = videoFile.name; // .mp4
        let videoTitle = videoFileName.split('.')[0];
        let uploaderName = document.getElementById('login_user_name').textContent;
        let uploaderEmail = document.getElementById('login_user_email').textContent;
        let duration = 24; // test
        let fileSize = videoFile.size;
        let lastModifiedDate = videoFile.lastModifiedDate;
        let relPath = videoFile.webkitRelativePath;


        let video_info = `
        <pre>VIDEO FILE: ${videoFileName}
            <small>            
            Uploader: ${uploaderName}
                      ${uploaderEmail}
            Duration: ${duration} seconds
            File size: ${fileSize} bytes
            File last modified: ${lastModifiedDate}
            File absolute path: ${videoUrl}
            </small>
        </pre>
        `;

        // console.log(video_info)
        document.getElementById('video-info').innerHTML = video_info;
        document.getElementById('upload_videoTitle').value = videoTitle;

        // create the video element dynamically, then load it.
        setPreviewWindow(videoUrl);
    }
}

function setPreviewWindow(blobUrl) {
    var videoEl = document.createElement('video');
    videoEl.src = blobUrl;
    videoEl.controls = true;
    videoEl.autoplay = true;
    videoEl.style.width = '18rem';
    // set video preview 
    var v_previewDiv = document.getElementById('preview_videoUpload');
    v_previewDiv.appendChild(videoEl);
}

function file_is_selected(e) {
    // capture the file path and set to the preview image
    // first upload the file to temp.
    var thumbnailInput = document.getElementById('edit_videoThumbnail_input');
    var tempFormData = new FormData();
    tempFormData.append('tmp_thumbnail', thumbnailInput.files[0]);
    tempFormData.append('video_action', 'temp_upload');
    tempFormData.append('video_id', 1); // default placeholder value


    var pageUrl = './php_actions/edit_video.php'
    backendRequest(pageUrl, tempFormData, false, true);


    // console.log(e)
    // console.log(this.files[0].mozFullPath) // only works in mozzila filefox browser!!
    // for security reason, browser cant access real file path!!
    // file must first be uploaded. then you can reference to it from uploads folder.

}

function animateProgressBar() {
    var progressBar = document.querySelector('.progress-bar');
    // animate the bar move from 0 to 100% 
    let width = 0; // starter

    function animate() {
        if (width >= 100) {
            clearInterval(intervalCounter); // stop the animation counter
        } else {
            width++;
            progressBar.style.width = width + '%';
            progressBar.setAttribute('aria-valuenow', width);
            progressBar.innerText = width + '%';
        }
    }

    const intervalCounter = setInterval(animate, 10); // adds 10 in every loop
}

function load_channel_metadata() {
    // update hidden fields
    var userlId = document.getElementById('login_user_id').textContent;
    var uploaderId = document.getElementById('uploader_id');
    uploaderId.value = userlId;

    // only one youtube channel is allowed under a single user account
    // use the user id to get the channel id
    var formData = new FormData();
    formData.append('user_id', userlId);
    var pageUrl = './php_actions/get_channel.php'
    backendRequest(pageUrl, formData, false, false, true)
}

function create_new_channel() {
    console.log('creating a new channel...')
    // set the user id
    var userId = document.getElementById('login_user_id').innerText;
    var channelOwnerInput = document.getElementById('channel_owner_id');
    channelOwnerInput.value = userId;
}


/*** GO LIVE ****/
const startLiveButton = document.getElementById('startLiveButton');
const stopLiveButton = document.getElementById('stopLiveButton');
const downloadRecordingButton = document.getElementById('downloadRecordingButton');
const recordingSpan = document.getElementById('recordingSpan');
const recordingPanel = document.getElementById('recordingPanel');
const previewPanel = document.getElementById('previewPanel');
const recordingLogs = document.getElementById('recordingLogs');
const recordingTimeLimit = 15000; // 15 seconds

// make sure document is already loaded
document.addEventListener("DOMContentLoaded", () => {
    // detect when go-live button is clicked
    startLiveButton.addEventListener("click", () => {
        // reveal the stop live button
        stopLiveButton.removeAttribute('hidden');
        // hide the start live button as the live is already underway.
        startLiveButton.style.display = 'none';
        // reveal recording span
        recordingSpan.removeAttribute('hidden');

        // **** start recording
        startRecording();
    });

    // detect when stop button is clicked
    stopLiveButton.addEventListener("click", () => {
        // reverse the visibility
        startLiveButton.removeAttribute('style'); // reveals the hidden start button
        downloadRecordingButton.removeAttribute('hidden'); // reveals the hidden download button

        stopLiveButton.style.display = 'none'; // hide stop button 
        recordingSpan.style.display = 'none';
        recordingPanel.style.display = 'none'; // hide the recording panel
        previewPanel.removeAttribute('hidden'); // reveals the preview panel
        

        // **** stop recording
        stop( recordingPanel.srcObject );
    });
});

function startRecording() {
    navigator.mediaDevices
        .getUserMedia({ // get the video and audio recorder media built onto the browser
            video: true,
            audio: true,
        })
        .then((stream) => { // setup the stream for continuous recording, asyc ... await
            // set the source of recording panel to the stream
            recordingPanel.srcObject = stream;
            // set the link to downloader button to the stream
            downloadRecordingButton.href = stream;
            // capture the camera stream
            recordingPanel.captureStream = recordingPanel.captureStream || recordingPanel.mozCaptureStream;
            return new Promise((resolve) => (recordingPanel.onplaying = resolve));
        })
        .then(() => startLiveRecording( // record live! sends chunks for data back
            recordingPanel.captureStream(),
            recordingTimeLimit
        ))
        .then((recordedChunks) => { // outputs recorded video to temp folder.
            let recordedBlob = new Blob(recordedChunks, { type: "video/webm" }); // temporary video/audio data
            previewPanel.src = URL.createObjectURL(recordedBlob); // creates temp url
            // set the link to downloader button to the stream
            downloadRecordingButton.href = previewPanel.src;
            downloadRecordingButton.download = "RecordedVideo.webm";

            let log = `Successfully recorded ${recordedBlob.size} bytes of ${recordedBlob.type} media.`;
            streamLog(log);
        })
        .catch((error) => { // for catching error while live streaming
            if (error.name === "NotFoundError") {
                let log = `Camera or microphone not found. Can't record.`;
                streamLog(log);
            } else {
                streamLog(error);
            }
        });
}

function startLiveRecording(stream, recordLimitMS) {
    let recorder = new MediaRecorder(stream);
    let data = [];

    recorder.ondataavailable = (event) => data.push(event.data); // updates array
    recorder.start(); // starts the live record from browser

    // record the stream length, how long it has been recording
    let log = `${recorder.state} for ${recordLimitMS / 1000} seconds...`;
    streamLog(log);

    let stopped = new Promise((resolve, reject) => {
        recorder.onstop = resolve; // check if recording is stopped
        recorder.onerror = (event) => reject(event.name);
    });

    let recorded = delay_wait(recordLimitMS)
        .then(() => {
            if (recorder.state === "recording") {
                recorder.stop(); // stops the live recording after few seconds delay
            }
        });

    return Promise.all([stopped, recorded]).then(() => data);
}

function streamLog(log) {
    // recording for 5 seconds
    // record stopped for 2 seconds
    recordingLogs.innerText += `\n${log}\n`; // new record log goes to new line
}

function delay_wait(delayMS) {
    // after stop button is pressed, delay stopping the recording for some time
    // no abrupt cutting. continues to records for few seconds
    return new Promise((resolve) => setTimeout(resolve, delayMS));
}

