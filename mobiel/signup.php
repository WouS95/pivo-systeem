<?php session_start();
if ($openedthroughindex != true) {
  die();
}
if ($_POST['submit']) {
   // gehashed password is gegenereerd uit wachtwoord
  $submittedusername = $_POST['username'];
  $submittedemail = $_POST['email'];
  $submittedfirstname = $_POST['firstname'];
  $submittedlastname = $_POST['lastname'];
  $submittedhashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
  include_once 'classes/serverdata.php';
  global $conn;
  global $sql;
  global $result;
  global $loginform;
  global $loggedin;
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed"
    //. $conn->connect_error
  );
  }
  $sql = "SELECT userId FROM Users WHERE username = '$submittedusername' OR email = '$submittedemail'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    echo "deze gebruikersnaam of email bestaat al, ben je je <a href='?resetpass=1'>wachtwoord vergeten?</a><br>";
    echo "<a href='index.php'>inloggen</a>";
  }

  else{
    include_once 'classes/serverdata.php';
    global $conn;
    global $sql;
    global $result;
    global $loginform;
    global $loggedin;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed"
      //. $conn->connect_error
    );
    }

  $sql = "INSERT INTO `Users` (`userId`, `username`, `email`, `firstName`, `lastName`, `passwordHashed`, `actief`, `seriesIdentifier`, `ipAdress`, `rights`) VALUES (NULL, '$submittedusername', '$submittedemail', '$submittedfirstname', '$submittedlastname', '$submittedhashedPassword', '1', NULL, NULL, '1');";
  $result = $conn->query($sql);
   $sql = "INSERT INTO ToPay ( userId ) SELECT userId FROM Users where username = '$submittedusername'";
   $result = $conn->query($sql);
  echo "Registratie gelukt!<br>";
  echo "<a href='index.php'>inloggen</a>";

}
} else
{

?>
  <?php include_once 'header.php'; ?>
  <section id="content">

    <h1>Account maken</h1>
    <form class="loginform" action="" method="post">
      <label for="username">Gebruikersnaam</label>
      <input required type="text" name="username" id="username" value="">
      <label for="email">Email adres</label>
      <input required type="text" name="email" id="email" value="">
      <label for="firstname">Voornaam</label>
      <input required type="text" name="firstname" id="firstname" value="">
      <label for="lastname">Achternaam</label>
      <input required type="text" name="lastname" id="lastname" value="">
      <label for="password">Wachtwoord</label>
      <input required type="password" name="password" id="password" value="">
      <input type="submit" name="submit" value="submit">
    </form>
    <p>Let op, je wachtwoord wordt gecodeerd opgeslagen en kan dus niet worden gezien of achterhaald.</p>
  </section>

</body>
<script src="scripts/custom.js"></script>
</html>
<?php
}
?>
