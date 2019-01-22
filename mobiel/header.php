<?php if (!$openedthroughindex): ?>
  <p>Hoi dit is niet de bedoeling!</p>
<?php else:
  $loggedIn = $_SESSION['loggedIn'];
  $userId = $_SESSION['userId'];
  ?>
  <!DOCTYPE html>
  <html>
  <head>
    <meta charset="UTF-8">
    <title>Tikbar</title>
    <link rel="apple-touch-icon" href="/custom_icon.png">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="Wouter Spaan - https://www.spaandesign.nl">
    <meta name="robots" content="noindex" />
    <link rel="stylesheet" href="css/style.css">
    <script src="scripts/jquery.js"></script>
    <script src="scripts/custom.js"></script>
  </head>
    <body>
  <header>
    <div class="homebutton">
      <a href="index.php">
      <img src="images/home.svg" alt="">
      </a>
    </div>
    <?php if ($loggedIn): ?>
    <div class="icons">
      <img class="opensettingsicon" src="images/settings.svg" alt="">
      <img hidden class="closesettingsicon" src="images/closesettings.svg" alt="">
      <?php if ($_SESSION['userRights'] == 2 ) { ?>
      <a href="index.php?admin=1" class="adminlink">
      Admin
      </a>
       <?php } ?>
      <script type="text/javascript">
        $('.opensettingsicon').click(function(event) {
          $(this).css('display','none');
          $('.closesettingsicon').css('display','block');
          $('#settingsmenu').addClass('visible')
        });
        $('.closesettingsicon').click(function(event) {
          $(this).css('display','none');
          $('.opensettingsicon').css('display','block');
          $('#settingsmenu').removeClass('visible')
        });
      </script>
    </div>
  <?php endif; ?>
  </header>
  <?php if ($loggedIn):
    require_once 'classes/serverdata.php';
    global $conn;
    global $sql;
    global $result;
    global $loginform;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT username, firstname, lastname, email, allowOthersToBuy, rights FROM Users where userId = $userId";
    $getuserinfo = $conn->query($sql);
    if ($getuserinfo->num_rows > 0) {
      while($row = $getuserinfo->fetch_assoc()) {
    ?>

  <div class="hiddensection" id="settingsmenu">
    <h3>Instellingen aanpassen</h3>
    <form class="changesettings" action="mobiel/changesettings.php" method="post">
      <label for="username">Gebruikersnaam</label>
      <input type="text" id="username" name="username" value="<?php echo $row['username']; ?>">
      <label for="firstname">Voornaam</label>
      <input type="text" id="firstname" name="firstname" value="<?php echo $row['firstname']; ?>">
      <label for="lastname">Achternaam</label>
      <input type="text" id="lastname" name="lastname" value="<?php echo $row['lastname']; ?>">
      <label for="email">E-mail adres</label>
      <input type="text" id="email" name="email" value="<?php echo $row['email']; ?>">
      <label for="allowotherstobuy">Anderen mogen <?php if ($row['allowOthersToBuy'] != 1){echo "<b>nog niet</b>";}else {echo "<b>wel</b>";} ?> voor mij bestellen met hun account</label>
      <input hidden type="checkbox" id="allowotherstobuy" name="allowotherstobuy"  <?php if ($row['allowOthersToBuy'] == 1){echo "checked";}?> value="">
      <span class="customcheckbox" onclick="toggleotherstobuy();"></span>
      <input type="submit" name="submit" value="Update">
      <!-- // laat settings zien die iedereen moet kunnen aanpassen
      // - username veranderen
      // - wachtwoord veranderen
      // - email veranderen
      // - Voornaam/achternaam veranderen -->
      <?php
      // set true or false for notice
      global $allowOthersToBuy;
      $allowOthersToBuy = $row['allowOthersToBuy'];
       ?>
<script>
function toggleotherstobuy()
 {
   var element = document.getElementById('allowotherstobuy');
   element.checked = !element.checked;
 }
</script>
    </form>
    <form class="changesettings" action="mobiel/changesettings.php" method="post">
      <label for="password">Wachtwoord veranderen</label>
      <input type="password" id="password" name="newpassword" value="">
      <input type="submit" name="submit" value="Wachtwoord veranderen">
    </form>
  </div>
  <?php   }}
endif; ?>
<?php endif;
?>
