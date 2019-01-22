<?php session_start(); ?>
<?php
date_default_timezone_set('Europe/Amsterdam');
$currentdatetime = date("Y-m-d H:i:s");
// echo $currentdatetime;
if (!isset($_SESSION['userId']) || $_SESSION['userId'] == "") {
  echo "<script>window.location.replace('logout.php');</script>";
  die();
}
$ownUserId = $_SESSION['userId'];
include_once '../classes/serverdata.php';
// $conn;
// $sql;
// $result;
// $loginform;
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  // die("Connection failed: " . $conn->connect_error);
  die("geen verbinding");
} ?>
<table>
<?php
$otherUser = $_POST['user'];
foreach ($otherUser as $value => $userId) {
  foreach ($_POST as $productId => $value) {
    if ($productId != "user") {
      if ($productId != "orderforothers") {
        if ($productId != "during") {
          if ($value == 1) {
          // echo "Doe iets voor de volgende gebruiker: ";
          // echo "$userId <br>";
          //     echo "For user: ";
          //     echo $userId;
          //     echo "<br>";
          //     echo "productid: ";
          //     echo $productId;
          //     echo "<br>";
          //     echo "value ";
          //     echo $value;
          //     echo "<br>";
          //     echo "userId: ";
          //     echo $ownUserId;
          //     echo "<br>";
          //     echo "Tijdens opkomst: ";
          //     echo $during;
          //     echo "<br>";
              while ($value == 1) {
                // echo $productId;
                // echo "<br>";
                $sql ="SELECT username, name FROM Users LEFT JOIN Products ON Products.productId = $productId WHERE Users.userId = $ownUserId";
                $getorderhistory = $conn->query($sql);
                if ($getorderhistory->num_rows == 1 ) {
                 while($row = $getorderhistory->fetch_assoc()) {
                   $during = $_POST['during'];
                   $message = $row['username'] . " heeft zojuist een " . $row['name'] . " besteld voor jou. Wil je dit niet? Dan kan je de bestelling ongedaan maken in de details laatst afgetikt pagina!";
                    $sql = "CALL CreateOrderForUser('$productId','$userId','$during','$ownUserId')";
                    $result = $conn->query($sql);

//                    $sql = "CALL SendUserMessage('$userId','$message')";
//                    $sendmessage = $conn->query($sql);
                    $sql = "INSERT INTO messages (receiverUserId, message, opened, messageMoment)
SELECT '$userId', '$message', '0', CURRENT_TIMESTAMP";
                    $sendmessage = $conn->query($sql);
                    
                    $value--;
                }
                 }
             }

          }
          }
        }
      }
    }
  }
// foreach( $_GET as $key => $value) {
//     if( is_array( $value ) ) {
//         foreach( $value as $user ) {
//             //echo "key: ";
//             echo "<br>";
//             echo "order for user: ";
//             echo $user;
//             echo "<br>";
//             echo "Bestel drankje: ";
//
//         }
//     } else {
//
//         echo $key;
//         echo ": ";
//         echo $value;
//         echo "<br>";
//
//     }
// }


// foreach ($_GET as $key => $value) {
//   for ($i=0; $i < $_GET['name'].length; $i++) {
//     // code...
//   }
//   echo "key: ";
//   echo $key;
//   echo "<br>";
//   echo "value: ";
//   echo $value;
//   echo "<br>";
// }
    // foreach ($_GET['user'] as $userId => $value) {
    //   $during = $_GET['during'];
    //   if ($value > 0) {
    //         echo "productid: ";
    //         echo $userId;
    //         echo "<br>";
    //         echo "value ";
    //         echo $value;
    //         echo "<br>";
    //         echo "userId: ";
    //         echo $ownUserId;
    //         echo "<br>";
    //         echo "Tijdens opkomst: ";
    //         echo $during;
    //         echo "<br>";
    //         while ($value > 0) {
    //           echo $userId;
    //           echo "<br>";
    //
    //          $sql = "CALL CreateOrderForUser('$productId','$otherUserId','$ownUserId','$during')";
    //          // $result = $conn->query($sql);
    //          $sql = "CALL UpdateToPay('$productId','$ownUserId','$during')";
    //          // $result = $conn->query($sql);
    //          $sql = "CALL MessageUser('$productId','$ownUserId','$during')";
    //          // $result = $conn->query($sql);
    //          $value--;
    //          echo "Order aangemaakt voor userid: " . $otherUserId;
    //        }
    //     }
    //   }
      // echo $sql;


 ?>
</table>
<br>
<br>
<br>
<?php //print_r($_POST); ?>
<?php //print_r($_GET); ?>
<script src="../scripts/jquery.js"></script>
<script src="../scripts/custom.js"></script>
<!-- <script>window.location.replace('../index.php');</script> -->
