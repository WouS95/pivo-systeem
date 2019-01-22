<?php session_start();
date_default_timezone_set('Europe/Amsterdam');
require_once '../classes/userdata.php';
require_once '../functions.php';
$openedthroughindex = true;
$currenttime = date("H:i:s");
$numberminuteslookback = -20;
$timesincecurrenttime =  date('H:i:s',strtotime($numberminuteslookback .'minutes',strtotime($currenttime)));
?>
<button type="button" id="reloadlist" name="reloadlist">Lijst herladen</button>
<?php
include_once '../classes/serverdata.php';
global $conn;
global $sql;
global $result;
global $loginform;
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  // die("Connection failed: " . $conn->connect_error);
  die("geen verbinding");
}else{

$sql = "SELECT firstName, lastName FROM OrderHistory LEFT JOIN Users ON Users.userId = OrderHistory.userId WHERE CAST(orderMoment AS TIME) BETWEEN '$timesincecurrenttime' and '$currenttime'";
$result = $conn->query($sql);

  if ($result->num_rows > 0) {
  echo "Aantal bestellingen afgelopen ". $numberminuteslookback." minuten: " .$result->num_rows."<br>";
  echo "<ul>";
    while($row = $result->fetch_assoc()) {
      echo "<li>".$row['firstName']." ".$row['lastName']."</li>";
    }
    echo "</ul>";
  }else {
    echo "geen bestellingen";
  }
} ?>


<?php echo "huidige tijd: ". $currenttime; ?>

<p>hier staat een lijst met mensen die hebben besteld via de app zodat de bierpakker op de desktop kan zien hoeveel en voor wie hij bier moet pakken.</p>
<p>nu nog met een reload knop (die nog niks doet). mogelijk later met een automatische check. Idee is dat wanneer de reloadknop is ingedrukt dat het script vanaf dat moment elke seconde nog een keer checkt in de database of er een bestelling is bijgekomen en deze dan laat zien. </p>
