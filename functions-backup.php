<?php session_start();
function mobileCheck(){

      if(isset($_SERVER['HTTP_USER_AGENT']) and !empty($_SERVER['HTTP_USER_AGENT'])){
         $user_ag = $_SERVER['HTTP_USER_AGENT'];
         if(preg_match('/(Mobile|Android|Tablet|GoBrowser|[0-9]x[0-9]*|uZardWeb\/|Mini|Doris\/|Skyfire\/|iPhone|Fennec\/|Maemo|Iris\/|CLDC\-|Mobi\/)/uis',$user_ag)){
           global $mobile;
           $mobile=true;
         }else{
            global $mobile;
            $mobile=false;
         }
      }else{
        global $mobile;
        $mobile=false;
  }


  // if ($mobile) {
  //   //mobiele versie
  //   require_once 'mobiel/index.php';
  // }
  // else{
  //   //desktop versie
  //   require_once 'desktop/index.php';
  // }
}

function loginWithCookies(){

    include_once 'classes/serverdata.php';
    global $conn;
    global $sql;
    global $result;
    global $loginform;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $seriesIdentifier = $_COOKIE['seriesIdentifier'];
    // $submittedhashedpassword = password_verify($submittedpassword, $hash);
    $sql = "SELECT memberId, username, voornaam, seriesIdentifier, ipAdress FROM leden WHERE seriesIdentifier = '$seriesIdentifier' AND actief = '1'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {

        if (password_verify($row["ipAdress"], $_COOKIE['secretToken'])) {
          echo "welkom ". $row["username"];
          echo "<br>"."id: " . $row["memberId"]. " - Username: " . $row["username"]."<br>"."voornaam: ". $row["voornaam"]."<br>";
          echo "Je ip adres waarmee je telefoon is geregistreerd is: ". $row['ipAdress'];
          $loggedinmemberid=$row["memberId"];
          $seriesIdentifier = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 60)), 0, 60); // seriesIdentifier is gegenereerd met een random nummer maker
          $secretToken = password_hash($row['ipAdress'], PASSWORD_DEFAULT); // secret token is gegenereerd uit ip adres
          setcookie('seriesIdentifier', $seriesIdentifier, time() + (86400 * 365), "/"); // 86400 = 1 day
          setcookie('secretToken', $secretToken, time() + (86400 * 365), "/"); // 86400 = 1 day
          $_SESSION["loggedIn"] = "true";
          $userId = $row["memberId"];
          //update in database: seriesIdentifier, ip-adres
          $sql = "UPDATE leden SET seriesIdentifier = '$seriesIdentifier' WHERE memberId = '$userId'";
          $result = $conn->query($sql);

        }else {
          echo "cookie login mislukt";
          $_SESSION["loggedIn"] = false;
          echo $loginform;
        }
    }

  }else {
    echo $loginform;
    $_SESSION["loggedIn"] = false;
  }
  mysqli_close($conn);
}

  function login(){

      include_once 'classes/serverdata.php';
      global $conn;
      global $sql;
      global $result;
      global $loginform;
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
      $submittedusername = $_POST['username'];
      $cookieusername = $_COOKIE['username'];
      $submittedpassword = $_POST['password'];
      // $submittedhashedpassword = password_verify($submittedpassword, $hash);
      $sql = "SELECT memberId, username, passwordhashed, seriesIdentifier FROM leden WHERE username = '$submittedusername' AND actief = '1'";
      $result = $conn->query($sql);

      if ($result->num_rows === 1) {
        while($row = $result->fetch_assoc()) {

          if (password_verify($submittedpassword, $row["passwordhashed"])) {
            echo "welkom ". $row["username"];
            echo "<br>"."id: " . $row["memberId"]. " - Name: " . $row["username"]. " - Pass: " . $row["passwordhashed"]. "<br>";
            echo "Je ip adres waarmee je telefoon is geregistreerd is: ". $_POST['ipadress'];
            $loggedinmemberid=$row["memberId"];
            $seriesIdentifier = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 60)), 0, 60); // seriesIdentifier is gegenereerd met een random nummer maker
            $secretToken = password_hash($_POST['ipadress'], PASSWORD_DEFAULT); // secret token is gegenereerd uit ip adres
            setcookie('seriesIdentifier', $seriesIdentifier, time() + (86400 * 365), "/"); // 86400 = 1 day
            setcookie('secretToken', $secretToken, time() + (86400 * 365), "/"); // 86400 = 1 day
            $_SESSION["loggedIn"] = "true";
            $userId = $row["memberId"];
            //update in database: seriesIdentifier, ip-adres
            $sql = "UPDATE leden SET seriesIdentifier = '$seriesIdentifier' WHERE memberId = '$userId'";
            $result = $conn->query($sql);

          }else {
            echo "Pass wrong";
            echo $loginform;
            $_SESSION["loggedIn"] = false;
          }
      }

    }else {
      echo $loginform;
      $_SESSION["loggedIn"] = false;
    }
    mysqli_close($conn);
}

function getIpAdress(){
      global $ip;
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
}
function logUserOut(){
    setcookie('seriesIdentifier', "", time() - (86400 * 365), "/"); // 86400 = 1 day
    setcookie('secretToken', "", time() - (86400 * 365), "/"); // 86400 = 1 day
    setcookie('secretToken', "", time() - (86400 * 365), "/"); // 86400 = 1 day
    $_SESSION["loggedIn"] = false;

}
 ?>
