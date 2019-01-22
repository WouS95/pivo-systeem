<?php session_start();
// laat settings zien die iedereen moet kunnen aanpassen
// - username veranderen
// - wachtwoord veranderen
// - email veranderen
// - Voornaam/achternaam veranderen

// laat extra settings zien die alleen beheerders kunnen aanpassen. dus als ze userrights van 2 hebben
// - aanpassen prijzen
// - aanpassen rechten anderen
// - toevoegen/verwijderen producten
// - producten activeren/deactiveren
// - gebruikers deactiveren/activeren
//
//
$userId = $_SESSION['userId'];
include_once '../classes/serverdata.php';
global $conn;
global $sql;
global $result;
global $loginform;
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_POST['submit'] && $_SESSION['loggedIn'] && $_POST['username'] && $_POST['firstname'] && $_POST['lastname'] && $_POST['email']) {
if ($_POST['allowotherstobuy'] === "") {
  $allowotherstobuy = 1;
}
else {
  $allowotherstobuy = 0;
}
$givenusername = $_POST['username'];
$givenfirstname = $_POST['firstname'];
$givenlastname = $_POST['lastname'];
$givenemail = $_POST['email'];
$sql="UPDATE Users SET username = '$givenusername', firstname = '$givenfirstname', lastname = '$givenlastname', email = '$givenemail', allowOthersToBuy = '$allowotherstobuy' WHERE userId = $userId;";
$execute = $conn->query($sql);
}
elseif ($_SESSION['loggedIn'] && $_POST['newpassword']) {
  $password = $_POST['newpassword'];
  $passwordhashed = password_hash($password, PASSWORD_DEFAULT);
  $sql = "UPDATE Users SET passwordHashed = '$passwordhashed' WHERE userId = '$userId';";
  // $result = $conn->query($sql);
  if (($result = $conn->query($sql)) !== FALSE){echo "Het is gelukt hoorr! <a href='../index.php'>Terug</a> ";}else {
    echo "Error: "
    // . $sql . "<br>" . $conn->error
    ;
  }
}

?>
<script>window.location.replace('../index.php');</script>
