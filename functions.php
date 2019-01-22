<?php session_start();
if ($_SESSION['loggedIn'] == 1) {
  $loggedin = true;
}
else {
  $loggedin = false;
}
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
    global $userRights;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
      //die("geen verbinding");
    }
    $seriesIdentifier = $_COOKIE['seriesIdentifier'];
    // $submittedhashedpassword = password_verify($submittedpassword, $hash);
    // $sql = "SELECT userId, username, voornaam, seriesIdentifier, ipAdress FROM leden WHERE seriesIdentifier = '$seriesIdentifier' AND actief = '1'";
    $sql = "SELECT userId, username, firstName, seriesIdentifier, ipAdress, rights FROM Users WHERE seriesIdentifier = '$seriesIdentifier' AND actief = '1'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
      while($row = $result->fetch_assoc()) {
        if (password_verify($row["ipAdress"], $_COOKIE['secretToken'])) {

          // echo "<br>"."id: " . $row["userId"]. " - Username: " . $row["username"]."<br>"."voornaam: ". $row["firstName"]."<br>";
          // echo "Je ip adres waarmee je telefoon is geregistreerd is: ". $row['ipAdress'];

          $userId = $row["userId"];
          $userRights = $row["rights"];
          $seriesIdentifier = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 60)), 0, 60); // seriesIdentifier is gegenereerd met een random nummer maker
          $secretToken = password_hash($row['ipAdress'], PASSWORD_DEFAULT); // secret token is gegenereerd uit ip adres
          setcookie('seriesIdentifier', $seriesIdentifier, time() + (86400 * 365), "/"); // 86400 = 1 day
          setcookie('secretToken', $secretToken, time() + (86400 * 365), "/"); // 86400 = 1 day
          $_SESSION['userId'] = $userId;
          $_SESSION['loggedIn'] = 1;
          $_SESSION['userRights'] = $userRights;
          //update in database: seriesIdentifier, ip-adres
          $sql = "UPDATE Users SET seriesIdentifier = '$seriesIdentifier' WHERE userId = '$userId'";
          $result = $conn->query($sql);
          // include_once 'mobiel/orderpagina.php';
          echo "<script>location.reload();</script>";


        }else {
          //flush all login-cookies
          echo "<script>window.location.replace('mobiel/logout.php');</script>";
        }
    }

  }else {
    //flush all login-cookies
    echo "<script>window.location.replace('mobiel/logout.php');</script>";
  }
  mysqli_close($conn);
}
  // function setupDatabaseConnection(){
  //   include_once 'classes/serverdata.php';
  //   global $conn;
  //   global $sql;
  //   global $result;
  //   global $loginform;
  //   global $row;
  //   $conn = new mysqli($servername, $username, $password, $dbname);
  //   if ($conn->connect_error) {
  //     die("Connection failed: " . $conn->connect_error);
  //   }
  //
  // }

  function login(){

      include_once 'classes/serverdata.php';
      global $conn;
      global $sql;
      global $result;
      global $loginform;
      global $loggedin;
      global $userRights;
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error) {
        die("Connection failed"
        . $conn->connect_error
      );
      }
      $submittedusername = mysqli_real_escape_string($conn, $_POST['username']);
      $cookieusername = $_COOKIE['username'];
      $submittedpassword = mysqli_real_escape_string($conn, $_POST['password']);
      $submittedipadress = mysqli_real_escape_string($conn, $_POST['ipadress']);
      // $submittedhashedpassword = password_verify($submittedpassword, $hash);
      $sql = "SELECT userId, username, passwordHashed, rights FROM Users WHERE username = '$submittedusername' AND actief = '1'";

      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {

          if (password_verify($submittedpassword, $row["passwordHashed"])) {
            $userId = $row["userId"];
            $userRights = $row['rights'];
            $seriesIdentifier = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 60)), 0, 60); // seriesIdentifier is gegenereerd met een random nummer maker
            $secretToken = password_hash($submittedipadress, PASSWORD_DEFAULT); // secret token is gegenereerd uit ip adres
            setcookie('seriesIdentifier', $seriesIdentifier, time() + (86400 * 365), "/"); // 86400 = 1 day
            setcookie('secretToken', $secretToken, time() + (86400 * 365), "/"); // 86400 = 1 day
            $_SESSION['loggedIn'] = 1;
            $_SESSION['userId'] = $userId;
            $_SESSION['userRights'] = $userRights;
            $loggedin = $_SESSION['loggedIn'];
            //update in database: seriesIdentifier, ip-adres
            $sql = "UPDATE `Users` SET `seriesIdentifier` = '$seriesIdentifier', `ipAdress` = '$submittedipadress' WHERE userId = '$userId'";
            $result = $conn->query($sql);

            // header("Refresh:0");
            echo "<script>location.reload();</script>";
          }
          else {
            echo "Gebruikersnaam of wachtwoord verkeerd";
            require_once 'mobiel/header.php';
            echo $loginform;
            $_SESSION['loggedIn'] = 0;
          }
      }
    }
    else {
      if ($_POST['submit']) {
        // code...

      echo "Gebruikersnaam of wachtwoord verkeerd";
}
$_SESSION['loggedIn'] = 0;
echo $loginform;
    }
}
//mysqli_close($conn);

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
function logUserOut() {
    setcookie('seriesIdentifier', "", time() - (86400 * 365), "/"); // 86400 = 1 day
    setcookie('secretToken', "", time() - (86400 * 365), "/"); // 86400 = 1 day
    setcookie('secretToken', "", time() - (86400 * 365), "/"); // 86400 = 1 day
    setcookie('userId', "", time() - (86400 * 365), "/"); // 86400 = 1 day
    $_SESSION['loggedIn'] = 0;
    session_destroy();
}
// function setUserData($row) {
//   require 'classes/serverdata.php';
//   //database connectie aanmaken
//   $conn = new mysqli($servername, $username, $password, $dbname);
//   if ($conn->connect_error) {
//     // die("Connection failed: " . $conn->connect_error);
//     die("geen verbinding");
//   }
//   else {
//     // userid uit de sessie halen
//     $userId = $_SESSION['userId'];
//     //userid, username en te betalen bedrag ophalen uit tabellen Users en ToPay
//       $sql = "SELECT Users.userId, username, toPay
//       FROM Users
//       LEFT JOIN ToPay ON ToPay.userId = Users.userId
//       WHERE Users.userId = $userId";
//       $result = $conn->query($sql);
//
//       if ($result->num_rows == 1) {
//           while($row = $result->fetch_assoc()) {
//           if ($row['toPay'] === NULL) {
//             $row['toPay'] = 0.00;
//           }
//           //return row naar orderpagina via de haakjes achter de functienaam
//           return $row;
//      }
//   }
//     else {
//       // Als er geen rijen gevonden zijn laat dit zien
//       echo "Er is iets misgegaan. Probeer opnieuw in te loggen";
//       echo "<script>window.location.replace('logout.php');</script>";
//       die();
//     }
//   }
// }

function getNumberOfOrdersPerProduct($row) {
  require 'classes/serverdata.php';
  //database connectie aanmaken
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    // die("Connection failed: " . $conn->connect_error);
    die("geen verbinding");
  }
  else {
    // userid uit de sessie halen

    $userId = $_SESSION['userId'];
    //userid, username en te betalen bedrag ophalen uit tabellen Users en ToPay
      $sql = "SELECT OrderHistory.userId, OrderHistory.productName,COUNT(OrderHistory.orderId) AS NumberOfOrders FROM OrderHistory WHERE OrderHistory.userId = 1 AND OrderHistory.deleted = 0 GROUP BY productName;";

      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        //print_r($row);
        // return $row;

          while($row = $result->fetch_assoc()) {
            // echo $row['productName'];
            // echo $row['NumberOfOrders'];


          //return row naar orderpagina via de haakjes achter de functienaam
          print_r($row);
          // return $row;

     }

   }
    }
    print_r($row);
}


function setUserData($row)
{
  $userId = $_SESSION['userId'];
  require 'classes/serverdata.php';
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    // die("geen verbinding");
  }
  else {
  $sql = "SELECT SUM(price) AS toPay, username FROM OrderHistory
LEFT JOIN Users ON Users.userId = OrderHistory.userId
WHERE OrderHistory.userId = '$userId' AND deleted = '0' AND setInInvoice = 0;";
  // $soortvanwerkendesqlstatement = "SELECT Users.userId, productId, firstName, lastName, username, toPay
  // FROM Users
  // LEFT JOIN OrderHistory ON OrderHistory.userId = Users.userId
  // LEFT JOIN ToPay ON ToPay.userId = Users.userId
  // WHERE OrderHistory.orderMoment BETWEEN DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -1 DAY) AND CURRENT_TIMESTAMP AND Users.userId = $userId";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
     while($row = $result->fetch_assoc()) {
      // echo "Product ID: " . $row['productId']."<br>";
      // echo "Voornaam: " . $row['firstName']."<br>";
      // echo "Achternaam: " . $row['lastName']."<br>";
      // echo "Tijd van bestellen: " . $row['orderMoment']."<br>";
      // echo "Tijdens opkomst: " . $row['during']."<br>";

      if ($row['toPay'] === NULL) {
        $row['toPay'] = 0.00;

      }
      return $row;
 }
}
else {
  echo "0 rijen";
}
  }
}
 ?>
