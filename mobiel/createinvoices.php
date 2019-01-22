<?php session_start();
if ($_SESSION['loggedIn']) {
if ($_SESSION['userRights'] == 2 ) {
  echo "Gefeliciteerd! je bent admin :)<br><br>";
    require_once '../classes/serverdata.php';
$conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

  $sql = "SELECT Users.userId, Users.firstName, Users.lastName, SUM(OrderHistory.price) AS totaalbedrag, DATE_FORMAT(OrderHistory.orderMoment, '%m-%Y') 'Maand en jaar' FROM Users LEFT JOIN OrderHistory ON OrderHistory.userId = Users.userId WHERE actief = 1 AND setInInvoice = 0 AND deleted = 0 GROUP BY DATE_FORMAT(OrderHistory.orderMoment, '%m-%Y') , Users.firstName, Users.lastName, Users.userId";
    $numusers = $conn->query($sql);
        if ($numusers->num_rows > 0) {
          while($row = $numusers->fetch_assoc()) {
              $userId = $row['userId'];
              $firstName = $row['firstName'];
              $lastName = $row['lastName'];
              $totaalbedrag = $row['totaalbedrag'];
              $invoiceTerm = $row['Maand en jaar'];
              $sql = "SELECT Users.firstName, Users.lastName, productName 'product', OrderHistory.during 'Tijdens opkomst', DATE_FORMAT(OrderHistory.orderMoment, '%m-%Y') 'Maand en jaar', COUNT(productId) 'hoeveelheid', SUM(price) 'subtotaal' FROM OrderHistory LEFT JOIN Users ON OrderHistory.userId = Users.userId WHERE OrderHistory.deleted = 0 AND OrderHistory.userId = $userId AND OrderHistory.setInInvoice = 0 AND DATE_FORMAT(OrderHistory.orderMoment, '%m-%Y') = '$invoiceTerm' GROUP BY productName, during, OrderHistory.userId,DATE_FORMAT(OrderHistory.orderMoment, '%m-%Y') ORDER BY OrderHistory.userId ASC";
              
                $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $alleBestellingen = "";
                      while($row = $result->fetch_assoc()) {
                          $hoeveelheid = $row['hoeveelheid'];
                          $productName = $row['product'];
                          $invoiceTerm = $row['Maand en jaar'];
                          if ($row['Tijdens opkomst']){
                              $during = "tijdens opkomst";
                          }else{$during = "buiten opkomst";}
                          $alleBestellingen .= $row['hoeveelheid'] ." ". $row['product'] ." ". $during. ", ";
                          //echo $row['hoeveelheid']
                        //  . " " .$row['product'] . " ";
                          if ($row['Tijdens opkomst']){
                         //     echo "Tijdens opkomst";
                          }else{//echo "Buiten opkomst";
                          }
                        //  echo " ".$row['Maand en jaar']."<br>";
                         // echo "Prijs: ".$row['subtotaal']."<br>";    
                         // echo '<br>';
                          
                          }
                        //verwijder laatste komma in opsomming van alle bestellingen
                        $alleBestellingen = rtrim($alleBestellingen, ', ');
                         $sql = "INSERT INTO `invoices` (`userId`, `invoiceTerm`, `payed`, `totalPrice`, `orderedProducts`, `firstLastName`) VALUES ('$userId', '$invoiceTerm', '0', '$totaalbedrag', '$alleBestellingen', '$firstName $lastName');";
//                          echo $sql;
              $createInvoices = $conn->query($sql);
              $sql = "UPDATE `OrderHistory` SET `setInInvoice` = '1' WHERE `userId` = '$userId' AND DATE_FORMAT(OrderHistory.orderMoment, '%m-%Y') = '$invoiceTerm';";
              $deleteFromOrderhistory = $conn->query($sql);
//            echo $sql;
            //  echo '<br><br>';
                      }
             
          }
        echo "Facturen genereren... Moment geduld, deze pagina sluit wanneer het klaar is."."<img src='../images/loading.svg' alt=''>"."<script>window.location.replace('../index.php?admin=1&invoicecreation=1');</script>";}else{
            echo "Geen facturen gemaakt ivm gebrek aan bestellingen"."<script>window.location.replace('../index.php?admin=1&invoicecreation=0');</script>";
        }
//          }}  
    

//$sql = "SELECT Users.firstName, Users.lastName, productName 'product', OrderHistory.during 'Tijdens opkomst', DATE_FORMAT(OrderHistory.orderMoment, '%m-%Y') 'Maand en jaar', COUNT(productId) 'hoeveelheid' FROM OrderHistory LEFT JOIN Users ON OrderHistory.userId = Users.userId WHERE OrderHistory.deleted = 0 GROUP BY productName, during, OrderHistory.userId,DATE_FORMAT(OrderHistory.orderMoment, '%m-%Y') ORDER BY OrderHistory.userId ASC";
//    $result = $conn->query($sql);
//        if ($result->num_rows > 0) {
//          while($row = $getorderhistory->fetch_assoc()) {
//              echo $row['']
//          }}
//SELECT Users.firstName, Users.lastName, productName 'product', OrderHistory.during 'Tijdens opkomst', DATE_FORMAT(OrderHistory.orderMoment, '%m-%Y') 'Maand en jaar', COUNT(productId) 'hoeveelheid' FROM OrderHistory LEFT JOIN Users ON OrderHistory.userId = Users.userId WHERE OrderHistory.deleted = 0 GROUP BY productName, during, OrderHistory.userId,DATE_FORMAT(OrderHistory.orderMoment, '%m-%Y') ORDER BY OrderHistory.userId ASC

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