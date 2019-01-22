<?php session_start();
require_once 'classes/userdata.php';
require_once 'functions.php';
$openedthroughindex = true;
if (!isset($_SESSION['userId']) || $_SESSION['userId'] == "") {
  echo "<script>window.location.replace('logout.php');</script>";
  die();
}
$userId = $_SESSION['userId'];
$limit = 25;
if ($_GET['limit'] == "0") {
  $limit = "10000000";
}
?>
    <div id="content">

      <h1>Mijn facturen</h1>
      <?php
      if ($_GET['limit'] != "0") {
          echo "<p>Maximaal 25</p> <a href='?invoices=1&limit=0'>Alles weergeven</a>";

      } else {
        echo "<p>Geen maximum aantal</p> <a href='?invoices=1'>Max 25 weergeven</a>";
      }?>


      <table class="blueTable">
        <?php
        $sql ="SELECT invoiceId, firstLastName, invoiceTerm, orderedProducts, payed, totalPrice FROM invoices
        where invoices.userId = $userId
        limit $limit
        ";
        $getorderhistory = $conn->query($sql);
        if ($getorderhistory->num_rows > 0) {
          ?>
          <tr>
            <th>Maand en jaar</th>
            <th>Bestelde producten</th>
            <th>Totaal te betalen</th>
            <th>Betaald</th>
            <th>Betaling goedgekeurd</th>
            
          </tr>
          <?php
          while($row = $getorderhistory->fetch_assoc()) {
            ?>
            <tr <?php if ($row['deleted']==1) {
              echo "style='color:#ccc;'";
            } ?>>
              <td><?php echo $row['invoiceTerm']; ?></td>
              <td><?php echo $row['orderedProducts']; ?></td>
              <td><?php echo $row['totalPrice']; ?></td>
              <td><?php echo $row['payed']; ?></td>
              <td><?php if($row['payed'] == $row['totalPrice']) {
                echo "Ja";
            }elseif($row['payed'] >= $row['totalPrice']){echo "Ja, teveel betaald!";}else{echo "Nee, nog niet";}
                  ?></td>
            </tr>

      <?php }}  ?>
      </table>
      <p>Graag het bedrag van de openstaande facturen overmaken op de juiste rekening. Stuur daarna een berichtje naar de penningmeester dat de betaling is voldaan. De penningmeester kan de betaling dan goedkeuren.</p>
    </div>
    <aside id="settingsmenu">
      <form class="" action="changesettings.php" method="post">
    </aside>
    </form>
  </body>
</html>
