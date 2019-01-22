<?php session_start();
if ($_SESSION['loggedIn']) {
  include_once '../classes/serverdata.php';
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    // die("Connection failed: " . $conn->connect_error);
    die("geen verbinding");
  }
  if ($_GET['orderid'] && $_GET['reactivateorder']) {
    $orderId = $_GET['orderid'];
    $sql="UPDATE OrderHistory SET deleted = '0' WHERE orderId = '$orderId';";
    $result = $conn->query($sql);
    $sql="UPDATE ToPay SET deleted = '0' WHERE orderId = '$orderId';";
    $result = $conn->query($sql);
  }
  elseif ($_GET['orderid'] && $_GET['deleteorder']) {
      $orderId = $_GET['orderid'];
      $sql="UPDATE OrderHistory SET deleted = '1' WHERE orderId = '$orderId';";
      $result = $conn->query($sql);
  }
}else {
  echo "Niet ingelogd";
}

?>
<script>window.location.replace('../index.php?stats=1');</script>
