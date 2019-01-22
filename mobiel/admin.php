<?php session_start();
if (!$openedthroughindex):
  ?>
  <p>Hoi dit is niet de bedoeling! <a href="../index.php?admin=1">Probeer het zo</a> </p>
<?php else:
if ($_SESSION['loggedIn']) {
if ($_SESSION['userRights'] == 2 ) {
//  echo "Gefeliciteerd! je bent admin :)";
  ?>
  <div id="adminsettingssections">
    <input type="radio" name="showsettingssection" value="products" id="products">
    <label for="products">Producten</label>
    <input type="radio" name="showsettingssection" value="users" id="users">
    <label for="users">Gebruikers</label>
    <input type="radio" name="showsettingssection" value="orderhistory" id="orderhistory">
    <label for="orderhistory">Facturen</label>
    <input type="radio" name="showsettingssection" value="othersettings" id="othersettings">
    <label for="othersettings">Overig</label>
  </div>
<div id="adminsettingsfields">
  <?php
  $sql = "SELECT name, productId, priceDuring, priceOutside FROM Products";
  $getproducts = $conn->query($sql);
  if ($getproducts->num_rows > 0) {
    ?>
    <div class="settingsectionitem" hidden id="productssection">
      <h4>Producten aanpassen (coming soon)</h4>
      <form hidden class="" action="mobiel/updateadminstuff.php" method="post">
        <?php
   while($row = $getproducts->fetch_assoc()) {
     ?>
     <div class="productssection">
        <label>Productnaam</label><br>
         <input type="text" name="product[]" value="<?php echo $row['name']; ?>"><br>
         <label>Prijs tijdens opkomst</label><br>
         <input type="text" name="priceduring[]" value="<?php echo $row['priceDuring']; ?>"><br>
         <label>Prijs buiten opkomst</label><br>
         <input type="text" name="priceoutside[]" value="<?php echo $row['priceOutside']; ?>"><br>
      </div>
     <?php
   }
   ?>
 <input type="submit" value="Prijzen aanpassen">
  </form>
 </div>
   <?php
 }else {
   echo "Geen producten";
 }
   $sql = "SELECT
    username,
    SUM(price) toPayInTotal
FROM
    OrderHistory
LEFT JOIN Users ON OrderHistory.userId = Users.userId WHERE OrderHistory.deleted = 0
GROUP BY
    OrderHistory.userId;";
   $getusers = $conn->query($sql);
   if ($getusers->num_rows > 0) {
     ?>
     <form class="" action="mobiel/createinvoices.php" method="post">
      <div class="settingsectionitem" hidden id="userssection">
      <h4>Gebruikersinfo aanpassen (coming soon)</h4>
      <ul>
        <?php while($row = $getusers->fetch_assoc()) { ?>
        <li><?php echo $row['username']. " &euro;" . $row['toPayInTotal'];?></li>
      <?php } ?>
      </ul>
    </div>
    </form>
  <?php } else {
    echo "Geen gebruikers";
  }
  $sql = "SELECT invoiceId, firstLastName, orderedProducts, payed, invoiceTerm, totalPrice, userId FROM `invoices` WHERE payed != totalPrice";
  $getinvoices = $conn->query($sql);
    ?>
      <div class="settingsectionitem" hidden id="facturen">
      <?php if ($getinvoices->num_rows > 0) { ?>
      <h4>Facturen</h4>
      <form id="changepayed" action="mobiel/updateinvoices.php" method="post">
<table class="blueTable">
        <?php
        if ($getinvoices->num_rows > 0) {
          ?>
          <tr>
            <th>Naam</th>
            <th>Periode</th>
            <th>Bestelde producten</th>
            <th>Totaal te betalen</th>
            <th>Betaald</th>
          </tr>

          <?php
          while($row = $getinvoices->fetch_assoc()) {
            ?>
            <tr>
              <td><?php echo $row['firstLastName']; ?></td>
              <td><?php echo $row['invoiceTerm']; ?></td>
              <td><?php echo $row['orderedProducts']; ?></td>
               <td><?php echo $row['totalPrice']; ?></td>
                <td>

                    <input class="" type="text" value="<?php echo $row['payed']; ?>" pattern="\d{1,3}\.\d{2}" name="valueToUpdate[]" required>
                    <input type="hidden" value="<?php echo $row['invoiceId']; ?>" name="invoiceIdToUpdate[]">
                    <input type="hidden" value="<?php echo $row['userId']; ?>" name="userIdToUpdate[]">

                </td>
                <?php } ?>
            </tr>

      <?php }}  ?>
      </table>
</form>
        <?php //while($row = $getinvoices->fetch_assoc()) {  ?>
        <!-- <li>  -->
          <?php //echo "Gebruiker: " . $row['firstLastName']."<br>".  "Besteld: " . $row['orderedProducts']."<br>".  "Te betalen: " . $row['totalPrice']."<br>".  "Al betaald: " . $row['payed']."<br>". $row['invoiceTerm']."<br>"; ?>
        <!-- </li> -->
      <?php //} ?>

      <?php //} else {
   // echo "Nog geen facturen gemaakt";

 // }  ?>
 <span class="updatestatus"></span>
   <h4>Facturen genereren voor iedereen</h4>
      <a hidden class="buttonstyle confirmlinkclick" href="mobiel/createinvoices.php">Facturen genereren voor iedereen</a>
    </div>


       <div class="settingsectionitem" hidden id="othersection">
      <h4>Andere dingen aanpassen (coming soon)</h4>
      <ul>
        <li>Opkomsttijden</li>
        <li>Bankrekening voor tikkie, ten name van</li>
      </ul>
    </div>
 </div>
  <script type="text/javascript">
  $(document).ready(function() {
  var hash = window.location.hash.substr(1);
  if (hash != "") {
    console.log(hash);
    $('#'+hash).prop("checked", true);
  }
  else {
    $('#products').prop("checked", true);
  }
  $('input, select, textarea').change(function() {
    $(this).addClass('changed');
    $(this).siblings('input').addClass('changed');
  });
  function settingsSectionChanged(){
      if ($('#adminsettingssections #products').prop('checked')) {
        $('#adminsettingsfields .settingsectionitem').hide();
        $('#adminsettingsfields #productssection').show();
        window.location.hash = '#products';
      }
      if ($('#adminsettingssections #users').prop('checked')) {
        $('#adminsettingsfields .settingsectionitem').hide();
        $('#adminsettingsfields #userssection').show();
          window.location.hash = '#users';
      }
      if ($('#adminsettingssections #orderhistory').prop('checked')) {
        $('#adminsettingsfields .settingsectionitem').hide();
        $('#adminsettingsfields #facturen').show();
          window.location.hash = '#orderhistory';
      }
      if ($('#adminsettingssections #othersettings').prop('checked')) {
        $('#adminsettingsfields .settingsectionitem').hide();
        $('#adminsettingsfields #othersection').show();
          window.location.hash = '#othersettings';
      }
    }
      settingsSectionChanged();
      $('input[type=radio]').change(function(){
        settingsSectionChanged();
      });

      $("#changepayed").change(function(e){
        var yourFormElement = $(this)[0];
    //yourFormElement.checkValidity();
    //yourFormElement.reportValidity();
    if (!yourFormElement.checkValidity()) {
    console.log("not updating");
    $('.updatestatus').html("Updaten <strong>niet</strong> gelukt, controleer op fouten!");
    return false;
  }
  $(document).on({
        ajaxStart: function() {
          $('.updatestatus').html("Bezig met automatisch opslaan...");
        },
        // ajaxStop: function() {  }
  });
  console.log("updating");
        $('input:not(.changed), textarea:not(.changed)').prop('disabled', true);
        e.preventDefault();
        $.ajax({
          url: 'mobiel/updateinvoices.php',
          type: 'POST',
          data: $('#changepayed').serialize(),
          async: true,
          timeout: 5000,
      }).fail(function () {
      $('.updatestatus').html("Automatisch opslaan <strong>niet</strong> gelukt! Netwerk error?");
      })
      .done(function () {
      $('.updatestatus').html("Automatisch opslaan gelukt!");
      $('input:not(.changed), textarea:not(.changed)').prop('disabled', false);
      $('.changed').removeClass('changed');
      }).always(function(){
      //document.getElementById( "changepayed" ).reset();
      });


      });

});



  </script>

  <?php
}
else {
  echo "Oei, je bent niet ingelogd als admin.. Vraag een admin om jou ook admin te maken als je dat heeeeel graag wilt..";
}
}
else {
  echo "<script>window.location.replace('../mobiel/logout.php');</script>";
}
endif;
 ?>
