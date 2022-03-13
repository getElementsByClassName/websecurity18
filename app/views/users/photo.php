
<?php
if(!isset($_SESSION)) { session_start(); }
$token = new Token();
$token->fnGenerateToken();
?>


<div class="container">
  <div class="row">
    <div class="margin-top"></div>
    <img class="responsive-img" src="<?php echo URLROOT.'images/photo/'.$data[0]->folder_path.'/'.$data[0]->file_path ?>" alt="">
  </div>
  <div class="row">

    <?php if ($data[0]->can_comment == null || $data[0]->can_comment == 1) : ?>

      <ul class="collection" >

        <?php if($data[0]->comment != null) : ?>
          <?php foreach ($data as $value) : ?>

            <li class="collection-item avatar">
              <img src="<?php echo URLROOT; ?>public/img/avatar.jpg" alt="" class="circle">
              <span class="title"><?php echo $value->first_name.' '.$value->last_name.' '.$value->created_at ?></span>
              <p><?php echo (Encrypt::fnDecrypt($value->comment, $value->iv)) ?></p>
            </li>

          <?php endforeach; ?>
        <?php endif; ?>

      </ul>

    <?php endif; ?>

  </div>

  <?php if($data[0]->can_comment == 1 || $data[0]->can_comment == null) : ?>

    <div class="row">
      <form id="frm-comment" class="col s12" enctype="application/x-www-form-urlencoded" data-photoid="<?php echo $data[0]->photo_id ?>">
        <div class="row">
          <div class="input-field col s12">
            <input id="comment" type="text" name="comment" class="validate">
            <label for="comment" class="">Do you like what you see?</label>
            <span class="helper-text" data-error="The comment is not valid" data-success="right">Your comment</span>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <input id="token" type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" class="validate">
          </div>
        </div>
        <div class="flex-end">
          <button id="btn-comment" class="btn waves-effect waves-light blue lighten-3" type="submit" name="action">Post Comment
          </button>
        </div>
      </form>
    </div>

  <?php endif; ?>
</div>
