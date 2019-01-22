<?php session_start();
//if (!$openedthroughindex): ?>
  <!-- <p>Hoi dit is niet de bedoeling!</p> -->
<?php //else: ?>
<?php
$sql = "SELECT * FROM Products";
$getproducts = $conn->query($sql);
require_once 'mobiel/header.php';
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
<form id="bestelformulier" action="mobiel/processorder.php" method="post">
<div class="binnenbuitenopkomst section">
  <input hidden type="radio" id="duringmeeting" name="during" value="1">
  <label for="duringmeeting">Tijdens opkomst</label>
  <input hidden type="radio" id="notduringmeeting" name="during" value="0">
  <label for="notduringmeeting">Buiten opkomst</label>
</div>
  <div class="producten section">
  <?php if ($getproducts->num_rows > 0) {
    while($row = $getproducts->fetch_assoc()) { ?>
        <input hidden min="0"  type="number" class="productitem" id="product<?php echo $row['name'] ?>" name="<?php echo $row['productId']; ?>" value="0" onchange="changeValue<?php echo $row['name'] ?>(this.value);">

          <span class="orderbutton" id="orderbutton<?php echo $row['name'] ?>">
            <span hidden class="minus">-</span>
            <span>
            <span id="value<?php echo $row['productId'] ?>"></span>
            <span><?php echo $row['name'] ?></span>
            </span>
            <span hidden class="plus">+</span>
            <script>
              // Genereer functie voor elk item dat te koop is die de waarde van het item en de waarde van het ingevulde inputveld bijhoudt.
              val<?php echo $row['productId'] ?> = 0;
              justclickedminus = false;
              function changeValue<?php echo $row['name'] ?>() {
                name = "<?php echo $row['name'] ?>";
                id = "<?php echo $row['productId'] ?>";
                val<?php echo $row['productId'] ?> = document.getElementById("product<?php echo $row['name'] ?>").value;
                if (val<?php echo $row['productId'] ?> <= 0) {
                  $('#value<?php echo $row['productId'] ?>').text("");
                }
                else {
                  $('#value<?php echo $row['productId'] ?>').text(val<?php echo $row['productId'] ?> );
                }

                 console.log(val<?php echo $row['productId'] ?>,name);
                 console.log('product id:',id);

                if (val<?php echo $row['productId'] ?>!=0) {
                  //laat plus en min zien
                    $("#orderbutton<?php echo $row['name'] ?> .plus, #orderbutton<?php echo $row['name'] ?> .minus").show(500);
                    $("#orderbutton<?php echo $row['name']?>").addClass('morethenzero');
                }
                else {
                  //plus en min verbergen
                  $("#orderbutton<?php echo $row['name'] ?> .plus, #orderbutton<?php echo $row['name'] ?> .minus").hide(500);
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
              });

              $("#orderbutton<?php echo $row['name'] ?> > .plus").click(function() {
                val<?php echo $row['productId'] ?> = document.getElementById("product<?php echo $row['name'] ?>").value;
                val<?php echo $row['productId'] ?>++;
                if (val<?php echo $row['productId'] ?> > 20) {
                  val<?php echo $row['productId'] ?> = 20;
                  alert("Maximum bereikt");
                }
                $('#product<?php echo $row['name'] ?>').val(val<?php echo $row['productId'] ?>);
                changeValue<?php echo $row['name'] ?>();
              });

              $("#orderbutton<?php echo $row['name'] ?> > .minus").click(function() {
                val<?php echo $row['productId'] ?> = document.getElementById("product<?php echo $row['name'] ?>").value;
                val<?php echo $row['productId'] ?>--;
                if (val<?php echo $row['productId'] ?> < 0) {
                  val<?php echo $row['productId'] ?> = 0;
                }
                $('#product<?php echo $row['name'] ?>').val(val<?php echo $row['productId'] ?>);
                changeValue<?php echo $row['name'] ?>();
                justclickedminus = true;
                myVar = setTimeout(function(){ justclickedminus = false }, 100);
              });

            </script>
          </span>

      <?php }} ?>
  </div>
<input type="submit" name="submit" value="Bestellen">
</form>
<a href="?orderforothers=1" data-transition="slide">Voor anderen bestellen</a>
<?php if ($allowOthersToBuy != 1){echo "<p>Sta anderen toe voor jou te bestellen in de instellingen!</p>";}?>

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

          e.preventDefault();
          $.ajax({
            url: 'mobiel/processorder.php',
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
