<?php session_start(); ?>
<?php
date_default_timezone_set('Europe/Amsterdam');
$currentdatetime = date("Y-m-d H:i:s");
// echo $currentdatetime;
if (!isset($_SESSION['userId']) || $_SESSION['userId'] == "") {
  echo "<script>window.location.replace('logout.php');</script>";
  die();
}
$userId = $_SESSION['userId'];
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
    foreach ($_POST as $productId => $value) {
      $during = $_POST['during'];
      if ($value > 0) {
            echo "productid: ";
            echo $productId;
            echo "<br>";
            echo "value ";
            echo $value;
            echo "<br>";
            echo "userId: ";
            echo $userId;
            echo "<br>";
            echo "Tijdens opkomst: ";
            echo $during;
            echo "<br>";
            while ($value > 0) {
              // echo $productId;
              // echo "<br>";

             $sql = "CALL CreateOrderForUser('$productId','$userId','$during','$userId')";
             // $result = $conn->query($sql);
             if (($result = $conn->query($sql)) !== FALSE){echo "Het is gelukt hoorr!";}else {
               echo "Error: "
                //. $sql . "<br>" . $conn->error
               ;
             }
             $sql = "CALL UpdateToPay('$productId','$userId','$during')";
             $result = $conn->query($sql);
             $value--;
           }
        }
      }
      // echo $sql;


 ?>
</table>
<br>
<br>
<br>
<?php //print_r($_POST); ?>
<!-- <a href="../index.php">terug</a> -->
<script src="../scripts/jquery.js"></script>
<script src="../scripts/custom.js"></script>
<script>//window.location.replace('../index.php');</script>
