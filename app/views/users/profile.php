<?php
if(!isset($_SESSION)) { session_start(); }
?>

<div class="container">
  <div class="row">
    <div id="profile-page" class="section">
      <!-- profile-page-header -->
      <div id="profile-page-header" class="card">
        <div class="card-image waves-effect waves-block waves-light">
          <img class="activator" src="<?php echo URLROOT; ?>public/img/user-profile-bg.jpg" alt="user background">
        </div>
        <figure class="card-profile-image">
          <img src="<?php echo URLROOT; ?>public/img/avatar.jpg" alt="profile image" class="circle z-depth-2 responsive-img activator">
        </figure>
        <div class="card-content">
          <div class="row">
            <div class="col s3 offset-s2">
              <h4 class="card-title grey-text text-darken-4"><?php echo htmlentities($_SESSION['user']->first_name).' '.htmlentities($_SESSION['user']->last_name) ?></h4>
              <p class="medium-small grey-text"><?php echo $_SESSION['user']->status ?> membership</p>
            </div>
            <div class="col s2 center-align">
              <h4 class="card-title grey-text text-darken-4"><?php echo $_SESSION['user']->albums ?></h4>
              <p class="medium-small grey-text">Albums</p>
            </div>
            <div class="col s2 center-align">
              <h4 class="card-title grey-text text-darken-4">0</h4>
              <p class="medium-small grey-text">Shared Albums</p>
            </div>
            <div class="col s2 center-align">
              <button id="btn-login" data-go-to="images/upload" class="btn waves-effect waves-light blue lighten-3 margin-top link" name="action">Create Album
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="divider"></div>
    <div class="section">
      <h5>Your Albums</h5>
      <ul class="collection">

        <?php foreach ($data[0] as $value) : ?>

          <li class="collection-item avatar">
            <div id="album_id" class=""><?php echo $value->album_id ?></div>
            <i class="material-icons circle album">folder</i>
            <span class="title"><?php echo $value->name ?></span>
            <p><?php echo $value->created_at ?><br>
              <?php echo $_SESSION['user']->first_name.' '.$_SESSION['user']->last_name ?>
            </p>
            <div class="right-align">
              <button class="share-album btn waves-effect waves-light blue lighten-3 " name="action">Share
              </button>
            </div>
          </li>

        <?php endforeach; ?>

      </ul>
    </div>
  </div>

  <div class="row">
    <div class="divider"></div>
    <div class="section">
      <h5>Albums shared with you</h5>
      <ul class="collection">

        <?php foreach ($data[1] as $value) : ?>

          <li class="collection-item avatar">
            <div id="shared_album_id" class=""><?php echo htmlentities($value->shared_album_id) ?></div>
            <i class="material-icons circle shared-album">folder</i>
            <span class="title"><?php echo $value->shared_album_name ?></span>
            <p><?php echo $value->shared_album_created_at ?><br>
             <?php echo $value->first_name.' '. $value->last_name  ?>
           </p>
           <p>
             <?php echo htmlentities($value->email) ?>
           </p>
         </li>

       <?php endforeach; ?>

     </ul>
   </div>
 </div>
</div>
</div>




