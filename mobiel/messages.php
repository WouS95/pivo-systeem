<?php session_start();
if (!$openedthroughindex):
  ?>
  <p>Hoi dit is niet de bedoeling! <a href="../index.php?messages=1">Probeer het zo</a> </p>
<?php else:
if (!$_SESSION['loggedIn']) {echo "<script>window.location.replace('../mobiel/logout.php');</script>"; die();}

if ($_SESSION['userRights'] == 2 ) {
  echo "Gefeliciteerd! je bent admin :)";
}
  $sql = "SELECT message FROM messages WHERE opened = 0 AND receiverUserId = $userId";
  $messages = $conn->query($sql);
  if ($messages->num_rows > 0) {
    while($row = $messages->fetch_assoc()) {
      ?>
      <table class="blueTable">
      <thead>
      <tr>
      <th>Tijd</th>
      <th>Van</th>
      <th>Bericht</th>

      </tr>
      </thead>
      <tbody>
      <tr>
      <td>cell1_1</td>
      <td>cell2_1</td>
      <td>cell3_1</td>

      </tr>
      </tbody>
      </table>
      <?php
    }
    }
endif;
 ?>
