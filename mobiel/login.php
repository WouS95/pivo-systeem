<?php session_start();
 if (!$openedthroughindex):
  die("nee");
  else:

getIpAdress();

$loginform = '<form class="loginform" action="" method="post">
  <label for="username">Gebruikersnaam</label>
  <input id="username" type="text" name="username" value="">
  <label for="password">Wachtwoord</label>
  <input type="password" name="password" id="password" value="">
  <input type="submit" name="submit" value="submit">
  <input type="hidden" name="ipadress" value="'.$ip.'">
</form>';
if ($_GET['login' == 'failed']) {
$loginform  .= "<p class='loginfail'>Verkeerde wachtwoord of gebruikersnaam</p>";
}

// inloggen: eerst checken of cookies aanwezig zijn met inlog gegevens
// als dat niet zo is het inlogformulier laten zien
require_once 'mobiel/header.php';
if (isset($_COOKIE['seriesIdentifier']) && isset($_COOKIE['secretToken'])) {
  // setupDatabaseConnection();

  loginWithCookies();

}else {
  // setupDatabaseConnection();
  login();
}


 endif; ?>
