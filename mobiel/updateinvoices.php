<?php session_start();
if ($_SESSION['loggedIn']) {
if ($_SESSION['userRights'] == 2 ) {
  echo "Gefeliciteerd! je bent admin :)<br><br>";
    require_once '../classes/serverdata.php';
$conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
  // $submittedUserId = $_GET['userIdToUpdate'];
  // $submittedInvoiceId = $_GET['invoiceIdToUpdate'];
  // $valueToUpdate = $_GET['valueToUpdate'];
  $submittedUserId = $_POST['userIdToUpdate'];
  $submittedInvoiceId = $_POST['invoiceIdToUpdate'];
  $valueToUpdate = $_POST['valueToUpdate'];

  for ($i=0;$i<count($submittedUserId);$i++)
  {
    // echo "userId: ";
    // echo $submittedUserId[$i];
    // echo $submittedInvoiceId[$i];
    // echo $valueToUpdate[$i];
    $thisSubmittedUserId = $submittedUserId[$i];
    $thisSubmittedInvoiceId = $submittedInvoiceId[$i];
    $thisValueToUpdate = $valueToUpdate[$i];
    $sql = "UPDATE `invoices` SET `payed` = '$thisValueToUpdate' WHERE `invoices`.`invoiceId` = '$thisSubmittedInvoiceId' AND `invoices`.`userId` = '$thisSubmittedUserId';";
      $updateInvoices = $conn->query($sql);
      // echo "<br>".$sql;
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
