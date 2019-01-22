<?php session_start();
if ($openedthroughindex != true) {
  die();
}
function currentPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

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
//echo currentPageURL();
if ($_POST['usernameoremail']) {
   // gehashed password is gegenereerd uit wachtwoord
  $submittedusernameoremail = $_POST['usernameoremail'];

  //$submittedhashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $sql = "SELECT userId, email, username FROM Users WHERE username = '$submittedusernameoremail' OR email = '$submittedusernameoremail'";
  $result = $conn->query($sql);
  if ($result->num_rows == 1) {
    while($row = $result->fetch_assoc()) {

      $emailadress = $row['email'];
      $fetchedusername = $row['username'];
      //user bestaat, stuur mail
      $seriesIdentifier = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 60)), 0, 60); // seriesIdentifier is gegenereerd met een random nummer maker
      $generatedresetlink = currentPageURL();
      $generatedresetlink = strtok($generatedresetlink, '?');
      if (strpos($generatedresetlink,'?resetpass=1') !== false) {
} else {
      $generatedresetlink.='?resetpass=1';
}
      $generatedresetlink .= "&key=";
      $generatedresetlink .= $seriesIdentifier;
      $generatedresetlink .= "&email=";
      $generatedresetlink .= $emailadress;

      $to = $emailadress;
      $subject = "Wachtwoord opnieuw instellen";

$message = "
<html>
<head>
<title>Wachtwoord opnieuw instellen</title>
</head>
<body>
<h3>Hallo " . $fetchedusername . " </h3>
<p>Klik hieronder om je wachtwoord opnieuw in te stellen.</p>
<a href=' " . $generatedresetlink . " '>Wachtwoord opnieuw instellen</a>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <pivos@markesteen.com>' . "\r\n";
mail($to,$subject,$message,$headers);
$sql = "UPDATE Users SET seriesIdentifier = '$seriesIdentifier' WHERE email = '$emailadress' AND username = '$fetchedusername'";
if (($result = $conn->query($sql)) !== FALSE){
echo "Hallo, als er een account is met de volgende username of email: <strong>" . $submittedusernameoremail. "</strong> is er een mail gestuurd naar het bijbehorende mailadres met een linkje waar je moet klikken. Dan kun je je wachtwoord aanpassen, fijn he :) <br><a href='index.php'>Terug naar inloggen</a>";}
else {
  echo "Error: "
  // . $sql . "<br>" . $conn->error
  ;
}
 $result = $conn->query($sql);
//  echo $message;
//echo "<br>";
//  echo $generatedresetlink;
//        echo "<br>";
//  echo $to;
//  echo $row['username'];
//        echo "<br>";
//  echo $sql;
//        echo "<br>";
     }
  }
echo "Hallo, als er een account is met de volgende username of email: <strong>" . $submittedusernameoremail. "</strong> is er een mail gestuurd naar het bijbehorende mailadres met een linkje waar je moet klikken. Dan kun je je wachtwoord aanpassen, fijn he :) <br><a href='index.php'>Terug naar inloggen</a>";
}
elseif ($_GET['key'] && $_GET['email']) {
include_once 'header.php';

    ?>
    <section id="content">
      <h1>Wachtwoord opnieuw instellen</h1>
      <form class="loginform" action="?resetpass=1" method="post">
        <label for="newpassword">Voer je nieuwe wachtwoord in</label>
        <input required type="password" name="newpassword" id="newpassword" value="">
        <input required type="hidden" name="emailadress" id="email" value="<?php echo $_GET['email'] ?>">
        <input required type="hidden" name="seriesIdentifier" id="seriesIdentifier" value="<?php echo $_GET['key'] ?>">
        <input type="submit" name="submit" value="submit">
      </form>
      <p>Let op, je wachtwoord wordt gecodeerd opgeslagen en kan dus niet worden gezien of achterhaald.</p>
    </section>

  </body>
  <script src="scripts/custom.js"></script>
  </html>
  <?php
}
elseif ($_POST['newpassword'] && $_POST['emailadress'] && $_POST['seriesIdentifier']) {
  $submittedemail = $_POST['emailadress'];
  $submittedseriesidentifier = $_POST['seriesIdentifier'];
  $sql = "SELECT userId FROM Users WHERE email = '$submittedemail' AND seriesIdentifier = '$submittedseriesidentifier' AND actief = '1'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
    $userId = $row['userId'];
    $password = $_POST['newpassword'];
    $seriesIdentifier = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 60)), 0, 60); // seriesIdentifier is gegenereerd met een random nummer maker
    $passwordhashed = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE Users SET seriesIdentifier = '$seriesIdentifier', passwordHashed = '$passwordhashed' WHERE userId = '$userId'";
    // $result = $conn->query($sql);
    if (($result = $conn->query($sql)) !== FALSE){echo "Het is gelukt hoorr! <a href='index.php'>Terug naar inloggen</a> ";}else {
      echo "Error: "
      // . $sql . "<br>" . $conn->error
      ;
    }

  }}
  else {
    echo "Er ging iets mis, probeer opnieuw..<br>";
    echo "<a href='?resetpass=1'>Wachtwoord opnieuw resetten</a>";
  }
}
else{
?>

<?php
include_once 'header.php';
  ?>
  <section id="content">
      <a href="index.php">Terug</a>
    <h1>Wachtwoord opnieuw instellen</h1>
    <form class="loginform" action="" method="post">
      <label for="username">Gebruikersnaam of email adres</label>
      <input required type="text" name="usernameoremail" id="username" value="">
      <input type="submit" name="submit" value="submit">
    </form>
  </section>

</body>
<script src="scripts/custom.js"></script>
</html>
<?php
}
?>
