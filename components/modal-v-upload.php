
<!-- Modal -->
<div class="modal fade modal-xl" id="videoUploadModal" tabindex="-1" aria-labelledby="videoUploadModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="videoUploadModalLabel">
            Upload Video
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">        
        
        <form action="./php_actions/upload_video.php" 
          class="was-validated border rounded p-4 m-2" 
          method="POST" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-12 text-center" id="upload-video"
            onclick="upload_video_file()">
              <input type="file" name="upload_videoInput" 
                id="upload_videoInput" onchange="reveal_hidden_content(event)" 
                hidden accept="video/mp4">
              <i class="fa fa-upload upload-btn my-5 text-secondary card-img-top"></i><br>
              Select file
            </div>

            <div class="col-md-12" id="hidden-upload-video" style="display: none;">
              <div class="row">
                <div class="col-md-6 ">
                  <!-- title  -->
                  <div class="mb-3">                
                    <label for="upload_videoTitle" class="form-label">
                      Title <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" 
                      id="upload_videoTitle" name="upload_videoTitle" required>
                    <div class="valid-feedback">
                      Title looks good!
                    </div>
                    <div class="invalid-feedback">
                      Please modify the title!
                    </div>

                    <!-- progress bar  -->
                    <div class="progress" role="progressbar" aria-label="Warning example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                      <div class="progress-bar progress-bar-striped progress-bar-animated text-bg-warning" style="width: 0%">0%</div>
                    </div>

                    <!-- video info  -->
                    <div class="my-4" id="video-info"></div>

                    <!-- visibility & schedule -->
                    <div class="form-check form-switch">
                      <div class="mb-3">
                        <small>Video visibility</small>
                        <input class="form-check-input" type="checkbox" role="switch" 
                          id="videoVisibility" onchange="open_video_scheduler()" name="videoVisibility" checked>
                        
                      </div>
                      
                      <div class="mb-3" id="videoSchedule_div" style="display: none;">
                        <small>Video schedule</small>
                        <input class="form-control" type="date" 
                          id="videoSchedule" name="videoSchedule">
                      </div>
                      
                    </div>

                  </div>

                  <!-- description  -->
                  <div class="mb-3">
                    <label for="upload_videoDescription" class="form-label">
                      Description <span class="text-danger">*</span>
                    </label>
                    <textarea name="upload_videoDescription" id="upload_videoDescription" 
                      class="form-control"
                      rows="5" required></textarea>
                    <div class="valid-feedback">
                      Description looks good!
                    </div>
                    <div class="invalid-feedback">
                      Please modify the description!
                    </div>
                  </div>
                </div>

                <div class="col-md-6 ">
                  <!-- preview window  -->
                  <div class="card" style="width: 18rem;">
                    <div class="card-img-top" id="preview_videoUpload">
                      <!-- <video controls autoplay>
                      <source src=""
                        type="video/mp4" class=""
                        alt="uploaded video" >
                    </video>                        -->
                    </div>
                    
                  </div>

                  <!-- thumbnail -->
                  <div class="card my-5 border" style="width: 18rem;">
                    <!-- upload new -->
                    <div class="mb-3">
                      <input type="file" name="upload_videoThumbnail" 
                        id="upload_videoThumbnail" onchange="set_video_thumbnail(event)" 
                        hidden accept="image/png">
                      <div class="card-body">
                        <a onclick="upload_video_tmb_file()" 
                          class="btn btn-outline-primary" id="upload_videoThumbnail_fa"> 
                          <i class="fa fa-upload"></i> Upload Thumbnail
                        </a>
                        <a onclick="upload_video_tmb_file()" 
                          class="btn btn-outline-primary" id="change_videoThumbnail_fa"
                          style="display: none;"> 
                          <i class="fa fa-pencil"></i> Change Thumbnail
                        </a>
                      </div>
                    </div>

                    <!-- change  -->  
                    <div class="mb-3">
                      <img src="" class="card-img-top" style="display: none;"
                        id="preview_upload_videoThumbnail" onclick="upload_tmb_file()">
                      <small id="upload_tmb_name" class="m-2"></small>
                    </div>        

                  </div>

                </div>
              </div>
              
              <button class="btn btn-primary" type="submit" data-bs-dismiss="modal">
                Upload video
              </button>
            </div>

          </div>

          <input type="text" hidden name="uploader_id" id="uploader_id">
          <input type="text" hidden name="channel_id" id="channel_id">
          
        </form>
      </div>

    </div>
  </div>
</div>