
<!-- Modal -->
<div class="modal fade modal-xl" id="createChannelModal" tabindex="-1" aria-labelledby="createChannelModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="createChannelModalLabel">
            Create YouTube Channel
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">        
        
        <form action="./php_actions/create_channel.php" 
          class="was-validated border rounded p-4 m-2" 
          method="POST" enctype="multipart/form-data">
          
          <div class="row" id="create-channel" >
            <!-- 
                send the following inputs to backend
                    channel_name -- text input
                    channel_description -- text area
                    channel_created_at -- capture today's date
                    channel_owner_fk -- user id, get it from hidden values in footer
            -->
            <!-- title  -->
            <div class="mb-3">                
                <label for="channel-name" class="form-label">
                    Channel Name <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" 
                    id="channel-name" name="channel-name" required>
                <div class="valid-feedback">
                    Channel name looks good!
                </div>
                <div class="invalid-feedback">
                    Please modify the channel name!
                </div>

            </div>

            <!-- description  -->
            <div class="mb-3">
                <label for="channel-description" class="form-label">
                    Channel Description <span class="text-danger">*</span>
                </label>
                <textarea name="channel-description" 
                    id="channel-description" class="form-control"
                    rows="5" required></textarea>
                <div class="valid-feedback">
                    Description looks good!
                </div>
                <div class="invalid-feedback">
                    Please modify the description!
                </div>
            </div>
            
            <input type="text" value="" hidden name="channel_owner_id" id="channel_owner_id">

            <button class="btn btn-outline-primary" type="submit" data-bs-dismiss="modal">
                Create channel
            </button>

          </div>
          
        </form>
      </div>

    </div>
  </div>
</div>