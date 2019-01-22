<?php session_start();
require_once 'classes/userdata.php';
require_once 'functions.php';
$openedthroughindex = true;
require_once 'mobiel/header.php';
  // mobileCheck();
  // if ($mobile) {
  //   //mobiele versie
  //   require_once 'mobiel/index.php';
  // }
  // else{
  //   //desktop versie
  //   require_once 'desktop/index.php';
  // };
if ($_GET['orderforothers'] == 1 && $_SESSION['loggedIn'] == 1) {
  require_once 'mobiel/orderforothers.php';
}
elseif ($_GET['stats'] == 1 && $_SESSION['loggedIn'] == 1) {
  require_once 'mobiel/stats.php';
}
elseif ($_GET['admin'] == 1 && $_SESSION['loggedIn'] == 1) {
  require_once 'mobiel/admin.php';
}
elseif ($_GET['messages'] == 1 && $_SESSION['loggedIn'] == 1) {
  require_once 'mobiel/messages.php';
}
elseif ($_GET['invoices'] == 1 && $_SESSION['loggedIn'] == 1) {
  require_once 'mobiel/invoices.php';
}
elseif ($_GET['signup'] == 1 && $_SESSION['loggedIn'] == 0) {
  require_once 'mobiel/signup.php';
}
elseif ($_GET['resetpass'] == 1 && $_SESSION['loggedIn'] == 0) {
  require_once 'mobiel/resetpass.php';
}
else{
  switch ($_SESSION['loggedIn']) {
    case 1:
      //require_once 'mobiel/header.php';
      require_once 'mobiel/orderpagina.php';
    break;
    case 0:
      // logUserOut();
      //require_once 'mobiel/header.php';
      require_once 'mobiel/login.php';

    break;
    default:
}
  }
  ?>

  <?php
  require_once 'mobiel/footer.php';

  // echo "<pre>";
  // print_r($GLOBALS);
  // echo "</pre>";
 ?>
</body>

</html>
