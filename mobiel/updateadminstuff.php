<?php session_start();
if ($_SESSION['loggedIn']) {
if ($_SESSION['userRights'] == 2 ) {
  echo "Gefeliciteerd! je bent admin :)<br><br>";
    require_once '../classes/serverdata.php';
$conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    
    // updaten die shit
    $product = $_GET['product'];
foreach ($product as $value => $productName) {
    echo "For product: ";
    echo $productName;
    echo "<br>";
}
    
}
  
else {
  //geen admin
  echo "<script>window.location.replace('../mobiel/logout.php');</script>";
}
}
else {
  echo "<script>window.location.replace('../mobiel/logout.php');</script>";
}
 ?>
<!--<script>window.location.replace('../index.php?admin=1&invoicecreation=1');</script>-->