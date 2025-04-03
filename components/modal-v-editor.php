
<!-- Modal -->
<div class="modal fade" id="videoEditorModal" tabindex="-1" aria-labelledby="videoEditorModal" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="videoEditorModalLabel">
            Edit Video
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        
        <form action="./php_actions/edit_video.php" 
          class="was-validated border rounded p-4 m-2" 
          method="POST" enctype="multipart/form-data">

          <input type="text" hidden id="video_id" name="video_id" value="">
          <input type="text" hidden id="video_action" name="video_action" value="save">
              
          <div class="row">
            <div class="col-md-6 ">
              <!-- title  -->
              <div class="mb-3">                
                <label for="edit_videoTitle" class="form-label">Title</label>
                <input type="text" class="form-control is-valid" id="edit_videoTitle" name="edit_videoTitle">
                <div class="valid-feedback">
                  Title looks good!
                </div>
                <div class="invalid-feedback">
                  Please modify the title!
                </div>
              </div>

              <!-- description  -->
              <div class="mb-3">
                <label for="edit_videoDescription" class="form-label">Description</label>
                <textarea name="edit_videoDescription" id="edit_videoDescription" class="form-control is-valid"
                rows="5"></textarea>
                <div class="valid-feedback">
                  Description looks good!
                </div>
                <div class="invalid-feedback">
                  Please modify the description!
                </div>
              </div>
            </div>

            <div class="col-md-6 ">
              <!-- thumbnail -->
              <div class="card" style="width: 18rem;">
                 <input type="file" name="edit_videoThumbnail_input" 
                 id="edit_videoThumbnail_input" onchange="file_is_selected(event)" hidden>

                <img src="" class="card-img-top" alt="thumbnail"
                  id="edit_videoThumbnail" onclick="upload_tmb_file()">
                  <small id="tmb_name">Thumbnail file name.png</small>
                <div class="card-body">
                  <a onclick="upload_tmb_file()" class="btn btn-primary"> 
                    <i class="fa fa-pencil"></i>
                    Change Thumbnail
                  </a>
                </div>
              </div>

            </div>
          </div>
            
          <button class="btn btn-primary" type="submit" data-bs-dismiss="modal">
            Save changes
          </button>
        </form>
          
        


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>