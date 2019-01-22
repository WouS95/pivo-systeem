<?php if (!$openedthroughindex): ?>
  <p>Hoi dit is niet de bedoeling!</p>
<?php else:
   ?>
  <footer>
    <?php if (!$loggedin) {
      ?>
      <a href='?signup=1'>Maak account</a>
      <a style="float:right;" href='?resetpass=1'>Wachtwoord vergeten</a>

      <?php
    }
    else {
      $getUserdata = setUserData($getData);
      // $numberOfOrders = getNumberOfOrdersPerProduct($getData);
      ?>
      <hr>
      <section id="userstats">
        <h4><img src="images/man-user.png" alt=""><?php echo $getUserdata['username']; ?></h4>
        <div class="bovensterij">
        <div class="dranktoday">
          <p>Vandaag:</p>
          <ul>
      <?php

        // userid uit de sessie halen

        $userId = $_SESSION['userId'];
        //userid, username en te betalen bedrag ophalen uit tabellen Users en ToPay
          $sql = "CALL BoughtInLastDay('$userId')";

          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            //print_r($row);
            // return $row;

              while($row = $result->fetch_assoc()) {
                // echo $row['productName'];
                // echo $row['NumberOfOrders'];

?>
  <li><?php echo $row['NumberOfOrders']." ".$row['productName']; ?></li>
<?php
              //return row naar orderpagina via de haakjes achter de functienaam
              // return $row;

         }}

      ?>

          </ul>
        </div>
        <div class="dranktotal">
          <p>Totaal:</p>
          <ul>
            <?php
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
              // die("Connection failed: " . $conn->connect_error);
              die("geen verbinding");
            }
            else {
            $sql = "CALL BoughtInTotal('$userId')";

            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              //print_r($row);
              // return $row;

                while($row = $result->fetch_assoc()) {
                  // echo $row['productName'];
                  // echo $row['NumberOfOrders'];

  ?>
              <li><?php echo $row['NumberOfOrders']." ".$row['productName'];}}
            }
            ?></li>
          </ul>
        </div>
        </div>
        <div class="ondersterij">
          <div class="topay">
            <p>Te betalen:</p>
            <p>Afgelopen periode:</p>
            <ul>

              <li>€ <?php echo $getUserdata['toPay']; ?>,-</li>
              <li>
                <!-- <a href="#">Nu betalen</a> Tikkie api itegratie!-->
              </li>
            </ul>
            <p>Te betalen aan facturen:</p>
            <ul>
            <?php
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
              // die("Connection failed: " . $conn->connect_error);
              die("geen verbinding");
            }
            $sql = "SELECT (SUM(totalPrice)-SUM(payed)) AS invoiceTotal FROM invoices where userId = '$userId'";
            $totalprice = $conn->query($sql);
        
                   if ($totalprice->num_rows > 0) {
              

                while($row = $totalprice->fetch_assoc()) {
                  // echo $row['productName'];
                  // echo $row['NumberOfOrders'];

  ?>
              <li><?php echo "€ ". $row['invoiceTotal']." ,-";}}else {echo "Nog geen facturen ";}
    ?>
<!--              <li>€ <?php// echo $getUserdata['toPay']; ?>,-</li>-->
              <li>
                <!-- <a href="#">Nu betalen</a> Tikkie api itegratie!-->
              </li>
            </ul>
          </div>
        <div class="more">
          <ul>
            <li><a href="?invoices=1">Mijn facturen</a></li>
            <li><a href="?stats=1">Details laatst afgetikt</a></li>
            <li><a href="mobiel/logout.php">Uitloggen</a></li>
          </ul>


        </div>

      </div>
      </section>

    <?php

// print_r($getUserdata);
   } ?>
   <div class="credits">
<p>Ontworpen en gebouwd door <a target="_blank" href="https://www.spaandesign.nl">Wouter Spaan</a></p>
 </div>
  </footer>
<?php endif;

?>
