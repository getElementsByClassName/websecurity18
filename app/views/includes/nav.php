  <nav class="white" role="navigation">
    <div class="nav-wrapper container">
      <a data-go-to="pages/home" id="logo-container" href="" class="brand-logo link">CloudImages</a>
      <ul class="right hide-on-med-and-down">
        <?php if(!Session::fnAuthenticateUser()) : ?>
          <li>
            <a data-go-to="users/register" class="link" href="">Register</a>
          </li>
          <li>
            <a data-go-to="users/login" class="link" href="">Log In</a>
          </li>
        <?php endif; ?>
        <?php if(Session::fnAuthenticateUser()) : ?>
          <li>
            <a data-go-to="premium" class="link" href="">Go Premium</a>
          </li>
          <li>
            <a data-go-to="users/profile" class="link" href="">Profile</a>
          </li>
          <li>
            <a class="logout" href="">Log out</a>
          </li>
        <?php endif; ?>
      </ul>

      <ul id="nav-mobile" class="sidenav">
        <li>
          <a href="">Register</a>
        </li>
        <li>
          <a href="">Log In</a>
        </li>
      </ul>
      <a href="#" data-target="nav-mobile" class="sidenav-trigger">
        <i class="material-icons">menu</i>
      </a>
    </div>
  </nav>
  <main>

