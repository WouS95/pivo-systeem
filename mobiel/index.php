<?php
$openedthroughindex = true;

if($_SESSION['loggedIn'] == 1) {
  echo 'hallo1';
  include_once 'orderpagina.php';
  echo "hallo2";
}
else {
  echo "doei1";
  include_once 'login.php';
  echo "doei2";
}

?>
