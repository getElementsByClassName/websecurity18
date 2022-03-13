
<div class="container">
  <div class="divider"></div>
  <div id="image-card" class="section">
    <h4 class="header"><?php echo htmlentities($data[0]->name) ?></h4>

    <?php foreach ($data as $value) : ?>

      <div class="row">
        <div class="col s12 m4">
          <div class="card" data-photoid="<?php echo $value->photo_id ?>" data-albumid="<?php echo $value->album_id ?>">
            <div class="card-image photo">
              <img src="<?php echo URLROOT.'images/photo/'.$value->folder_path.'/'.$value->file_path ?>" alt="">
            </div>
          </div>
        </div>

      <?php endforeach; ?>

    </div>
  </div>

