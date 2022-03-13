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
            <span class="card-title">Sign Up</span>
            <div class="row">
              <form id="frm-register" class="col s12" enctype="application/x-www-form-urlencoded">
                <div class="row">
                  <div class="input-field col s12">
                    <input id="first_name" type="text" name="first_name" class="validate">
                    <label for="first_name" class="" >First Name</label>
                    <span class="helper-text" data-error="Your first name can not be empty, and can only contain letters">Your first name</span>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                    <input id="last_name" type="text" name="last_name" class="validate">
                    <label for="last_name" class="">Last Name</label>
                    <span class="helper-text" data-error="Your last name can not be empty, and can only contain letters" data-success="right">Your last name</span>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                    <input id="email" type="email" name="email" class="validate">
                    <label for="email" class="">Email</label>
                    <span class="helper-text" data-error="Seems like your email is not a valid e-mail" data-success="right">Your email</span>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                    <input id="password" type="password" name="password" class="validate">
                    <label for="password" class="">Password</label>
                    <span class="helper-text" data-error="Password must be at least 8 chars, contain 1 upper case, 1 lower case and one number or special char" data-success="right">Password</span>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                    <input id="password_confirm" type="password" name="password_confirm" class="validate">
                    <label for="password_confirm" class="">Password Confirm</label>
                    <span class="helper-text" data-error="Does your passwords match?" data-success="right">Retype password</span>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                    <input id="token_register" type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" class="validate">
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                    <div class="g-recaptcha" data-theme="light" data-sitekey="6LfSNF4UAAAAAJw0bZtQGA0G1DyCajxlZKy8ce_e"></div>
                  </div>
                  <script nonce="1234" src='https://www.google.com/recaptcha/api.js'></script>
                </div>
                <div class="flex-end">
                  <button id="btn-register" class="btn waves-effect waves-light blue lighten-3" type="submit" name="action">Sign me up
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

