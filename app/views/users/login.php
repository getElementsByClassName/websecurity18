<?php
if(!isset($_SESSION)) { session_start(); }
$token = new Token();
$token->fnGenerateToken();
?>

<div class="bg">
  <div class="container">
    <div class="row">
      <div class="col s12 m6 offset-m3">
        <div class="card white">
          <div class="card-content orange-text text-accent-2">
            <span class="card-title">Log In</span>
            <div class="row">
              <form id="frm-login" class="col s12" enctype="application/x-www-form-urlencoded">
                <div class="row">
                  <div class="input-field col s12">
                    <input id="email" type="email" name="email" class="validate">
                    <label for="email" class="">Email</label>
                    <span class="helper-text" data-error="Is this the e-mail you signed up with?" data-success="right">Your e-mail</span>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                    <input id="password" type="password" name="password" class="validate">
                    <label for="password" class="">Password</label>
                    <span class="helper-text" data-error="Is this your right password?" data-success="right">Your password</span>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                    <input id="token" type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" class="validate">
                  </div>
                </div>
                <div class="flex-end">
                  <button id="btn-login" class="btn waves-effect waves-light blue lighten-3" type="submit" name="action">Submit
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

