<?php session_start();
if (!$openedthroughindex): ?>
  <p>Hoi dit is niet de bedoeling!</p>
<?php else: ?>


</style>
<?php
require_once 'header.php';
 ?>
 <div class="ajaxloader" id="loadingsuccess">
   <img src="images/success.svg" alt="">
   <p>Gelukt, proost!</p>
 </div>
 <div class="ajaxloader" id="loadinginprogress">
   <img src="images/loading.svg" alt="">
   <p>Laden...</p>
 </div>
 <div class="ajaxloader" id="loadingfailed">
   <img src="images/fail.svg" alt="">
   <p>Er ging iets mis, probeer opnieuw!</p>
 </div>
 <section id="content">

<form id="bestelformulier" action="mobiel/processorderforothers.php" method="post">
  <input hidden type="radio" id="orderforothers" name="orderforothers" value="1" checked>
<div class="binnenbuitenopkomst section">
  <input hidden type="radio" id="duringmeeting" name="during" value="1">
  <label for="duringmeeting">Tijdens opkomst</label>
  <input hidden type="radio" id="notduringmeeting" name="during" value="0">
  <label for="notduringmeeting">Buiten opkomst</label>
</div>
<div class="gebruikers section">
  <h2>Voor wie wil je bestellen?</h2>
<?php
$sql = "SELECT username, firstName, userId FROM Users WHERE allowOthersToBuy = 1";
$getproducts = $conn->query($sql);
if ($getproducts->num_rows > 0) {
 while($row = $getproducts->fetch_assoc()) {
   ?>
   <input hidden type="checkbox" class="useritem" id="userbutton<?php echo $row['username'] ?>" name="user[]" value="<?php echo $row['userId']; ?>">
     <label for="userbutton<?php echo $row['username'] ?>" id="username<?php echo $row['username'] ?>">
       <span class="usernamebutton" id="userbutton<?php echo $row['username'] ?>">
       <span><?php echo $row['username']. " (<i>". $row['firstName'] . ")</i> " ?></span>
       </span>
</label>
     <?php
 }}else {
   echo "Niemand wil een biertje van jou!";
 }?>
</div>
  <div class="producten section">
    <h2>Wat wil je voor diegene(n) bestellen?</h2>
  <?php
  $sql = "SELECT * FROM Products";
  $getproducts = $conn->query($sql);
   if ($getproducts->num_rows > 0) {
    while($row = $getproducts->fetch_assoc()) { ?>
        <input hidden min="0" max="1" type="number" class="productitem" id="product<?php echo $row['name'] ?>" name="<?php echo $row['productId']; ?>" value="" onchange="changeValue<?php echo $row['name'] ?>(this.value);">
          <span class="orderbutton" id="orderbutton<?php echo $row['name'] ?>">
            <span>
            <span id="value<?php echo $row['productId'] ?>" class="values"></span>
            <span><?php echo $row['name'] ?></span>
            </span>
            <script>
              // Genereer functie voor elk item dat te koop is die de waarde van het item en de waarde van het ingevulde inputveld bijhoudt.
              val<?php echo $row['productId'] ?> = "";
              justclickedminus = false;
              function changeValue<?php echo $row['name'] ?>() {
                $(".orderbutton").removeClass('morethenzero');
                $('.productitem:not("#product<?php echo $row['name'] ?>")').val("");
                $('.values').text("");
                name = "<?php echo $row['name'] ?>";
                id = "<?php echo $row['productId'] ?>";
                val<?php echo $row['productId'] ?> = document.getElementById("product<?php echo $row['name'] ?>").value;
                if (val<?php echo $row['productId'] ?> <= 0) {
                  $('#value<?php echo $row['productId'] ?>').text("");
                  $(val<?php echo $row['productId'] ?>).val("");
                }
                else if (val<?php echo $row['productId'] ?> >= 1) {

                  // $(".orderbutton").removeClass('morethenzero');
                  $('#value<?php echo $row['productId'] ?>').text("1");
                }
                else {
                  $('#value<?php echo $row['productId'] ?>').text(val<?php echo $row['productId'] ?> );
                }

                 console.log(val<?php echo $row['productId'] ?>,name);
                 console.log('product id:',id);

                if (val<?php echo $row['productId'] ?>!=0) {
                  //word groen
                    $("#orderbutton<?php echo $row['name']?>").addClass('morethenzero');
                }
                else {
                  //word niet meer groen
                  $("#orderbutton<?php echo $row['name']?>").removeClass('morethenzero');
                }

              }

              $("#orderbutton<?php echo $row['name'] ?>").click(function() {
                if (val<?php echo $row['productId'] ?> == 0 && !justclickedminus) {
                val<?php echo $row['productId'] ?> = document.getElementById("product<?php echo $row['name'] ?>").value;
                val<?php echo $row['productId'] ?>++;
                $('#product<?php echo $row['name'] ?>').val(val<?php echo $row['productId'] ?>);
                changeValue<?php echo $row['name'] ?>();
              }
                else if (val<?php echo $row['productId'] ?> > 0 && !justclickedminus) {
                val<?php echo $row['productId'] ?> = document.getElementById("product<?php echo $row['name'] ?>").value;
                val<?php echo $row['productId'] ?> = "";
                $('#product<?php echo $row['name'] ?>').val(val<?php echo $row['productId'] ?>);
                changeValue<?php echo $row['name'] ?>();
              }
              });

            </script>
          </span>

      <?php }} ?>
  </div>
<input type="submit" name="submit" value="Bestellen" id="bestellen">
</form>
<!-- <a href="#">Voor anderen bestellen</a> -->
<?php if (!$allowOthersToBuy){echo "<p>Sta anderen toe voor jou te bestellen in de instellingen!</p>";}?>
</section>
<?php //endif; ?>
<script>
$(document).on({
      ajaxStart: function() { $('#loadinginprogress').show(0); },
      ajaxStop: function() { $('#loadinginprogress').hide(0); }
});

$( "#loadingfailed" ).click(function() {
  $('#loadingfailed').hide(500);
});
$(function () {
        $('#bestelformulier').on('submit', function (e) {
          var els = $('.producten :input[type=number]').filter(function() {
            return this.value !== "" && this.value !== "0";
  });

        if (els.length == 0) {
          alert("Klik een product aan!");
          return false;
        }
        checked = $(".gebruikers input[type=checkbox]:checked").length;
        console.log(checked);
        if(!checked) {
          alert("Klik mininaal een naam aan!");
          return false;
        }

         e.preventDefault();
          $.ajax({
            url: 'mobiel/processorderforothers.php',
            type: 'POST',
            data: $('#bestelformulier').serialize(),
            async: true,
            timeout: 5000,

  }).fail(function () {
    $('#loadingfailed').show(500);
              setTimeout(
                  function(){
                      $('#loadingfailed').hide(500);

                  },
                  10000);
})
.done(function () {
  $('#loadingsuccess').show(500);
           setTimeout(
               function(){
                   location.reload();
               },
               2000);
}).always(function(){
  document.getElementById( "bestelformulier" ).reset();
});


        });

      });

       setTimeout(
           function(){
               document.getElementById( "bestelformulier" ).reset();
           },
           );
// huidige dag bepalen, 1 is maandag, 2 is dinsdag ect.
$(document).ready(function() {
    var date = new Date();
    var currentDay = date.getDay();
    var currentTime = date.getHours();
    if (currentDay == 5) { //5 voor vrijdag
      if (currentTime >= 17 && currentTime <= 24) { //tussen 5 uur smiddags en 24 uur snachts
         document.getElementById("duringmeeting").checked = true;
      } else {
          document.getElementById("notduringmeeting").checked = true;
      }
    }
    else if (currentDay == 6) { // 6 voor zaterdag
      if (currentTime >= 0 && currentTime <= 6) { //tussen 0 uur snachts en 6 uur sochtends
         document.getElementById("duringmeeting").checked = true;
      } else {
          document.getElementById("notduringmeeting").checked = true;
      }
    }
    else {
      document.getElementById("notduringmeeting").checked = true;
    }
});

</script>
<?php endif; ?>
