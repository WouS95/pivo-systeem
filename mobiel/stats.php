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

      <h1>Jouw bestelgeschiedenis</h1>
      <?php
      if ($_GET['limit'] != "0") {
          echo "<p>Maximaal 25</p> <a href='?stats=1&limit=0'>Alles weergeven</a>";

      } else {
        echo "<p>Geen maximum aantal</p> <a href='?stats=1'>Max 25 weergeven</a>";
      }?>


      <table class="blueTable">
        <?php
        $sql ="SELECT orderId, username, productName, orderedBy, orderMoment, during, deleted FROM OrderHistory
        LEFT JOIN Users ON Users.userId = orderedBy
        where OrderHistory.userId = $userId AND setInInvoice = 0 order by OrderHistory.orderMoment desc
        limit $limit
        ";
        $getorderhistory = $conn->query($sql);
        if ($getorderhistory->num_rows > 0) {
          ?>
          <tr>
            <th>Datum en tijd</th>
            <th>Wat</th>
            <th>Besteld door</th>
            <th>Tijdens opkomst</th>
          </tr>
          <?php
          while($row = $getorderhistory->fetch_assoc()) {
            ?>
            <tr <?php if ($row['deleted']==1) {
              echo "style='color:#ccc;'";
            } ?>>
              <td><?php echo $row['orderMoment']; ?></td>
              <td><?php echo $row['productName']; ?></td>
              <td><?php if ($row['orderedBy']==0) {
                echo "Jezelf";
              }else {
                echo $row['username'];
              } ?></td>
              <td><?php if ($row['during']==1) {
                echo "Ja";
              }else {
                echo "Nee";
              } ?></td>
                <td id="deleteorder<?php echo $row['orderId']; ?>" style="width:5%;">
                  <?php if ($row['deleted']==0) {?>
                    <a href="mobiel/deleteorder.php?deleteorder=1&orderid=<?php echo $row['orderId']; ?>">
                  <img src="images/fail.svg" alt="verwijderen">
                  </a>
                <?php  }
                else { ?>
                  <a href="mobiel/deleteorder.php?reactivateorder=1&orderid=<?php echo $row['orderId']; ?>">
                <img src="images/success.svg" alt="terugzetten">
                </a>
                <?php }?>

               </td>

            </tr>

      <?php }}  ?>
      </table>
    </div>
    <aside id="settingsmenu">
      <form class="" action="changesettings.php" method="post">
    </aside>
    </form>
  </body>
</html>
