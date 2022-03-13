<?php
if(!isset($_SESSION)) { session_start(); }
$token = new Token();
$token->fnGenerateToken();

$MAX_FILE_SIZE = 4194304 ; // 4 MB (in bytes)

?>

<!-- <form id="frm-upload" method="post" enctype="multipart/form-data">
  <p>
   <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $MAX_FILE_SIZE ?>">
   <label for="upload">Select File:</label>
   <input id="upload" name="files[]" type="file"  multiple/>
   <input id="album_name" type="text" name="album_name" class="validate">
   <label for="album_name" class="" >Name of album</label>
   <input id="token" type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" class="validate">
 </p>
 <div class="flex-end">
  <button id="btn-upload" class="btn waves-effect waves-light blue lighten-3" type="submit" name="upload">Upload
  </button>
</div>
</form> -->


<div class="bg">
  <div class="container">
    <div class="row">
      <div class="col s12 m6 offset-m3">
        <div class="card white">
          <div class="card-content orange-text text-accent-2">
            <span class="card-title">Upload Images</span>
            <div class="row">
              <form id="frm-upload" method="post" class="col s12" enctype="multipart/form-data">
                <div class="row">
                  <div class="input-field col s12">
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $MAX_FILE_SIZE ?>">
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                    <label for="upload" class="">Select image files:</label>
                    <input id="upload" name="files[]" type="file" multiple/>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                    <label for="album_name" class="">Name of album</label>
                    <input id="album_name" name="album_name" type="text"/>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                    <input id="token" type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" class="validate">
                  </div>
                </div>
                <div class="flex-end">
                  <button id="btn-upload" class="btn waves-effect waves-light blue lighten-3" type="submit" name="upload">Upload
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


