<?php session_start();
if ($_SESSION['loggedIn']) {
if ($_SESSION['userRights'] == 2 ) {

require_once '../classes/serverdata.php';

//Connect to MySQL using PDO.
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

//Create our SQL query.
//$sql = "SELECT * FROM OrderHistory";
$sql =  "SELECT  firstName AS 'Voornaam', lastName AS 'Achternaam', productName AS 'Product', DATE_FORMAT(orderMoment, '1-%m-%y') AS 'Maand-jaar', price AS 'prijs', during AS 'tijdens-opkomst' FROM OrderHistory
        LEFT JOIN Users ON Users.userId = OrderHistory.userId
        WHERE deleted = 0
        order by OrderHistory.orderMoment desc";

//Prepare our SQL query.
$statement = $pdo->prepare($sql);

//Executre our SQL query.
$statement->execute();

//Fetch all of the rows from our MySQL table.
$rows = $statement->fetchAll(PDO::FETCH_ASSOC);

//Get the column names.
$columnNames = array();
if(!empty($rows)){
    //We only need to loop through the first row of our result
    //in order to collate the column names.
    $firstRow = $rows[0];
    foreach($firstRow as $colName => $val){
        $columnNames[] = $colName;
    }
}

//Setup the filename that our CSV will have when it is downloaded.
$fileName = 'bestellingen-export.csv';

//Set the Content-Type and Content-Disposition headers to force the download.
header('Content-Type: application/excel');
header('Content-Disposition: attachment; filename="' . $fileName . '"');

//Open up a file pointer
$fp = fopen('php://output', 'w');

//Start off by writing the column names to the file.
fputcsv($fp, $columnNames);

//Then, loop through the rows and write them to the CSV file.
foreach ($rows as $row) {
    fputcsv($fp, $row);
}

//Close the file pointer.
fclose($fp);

}
else {
  echo "Oei, je bent niet ingelogd als admin.. Vraag een admin om jou ook admin te maken als je dat heeeeel graag wilt..";
}
}
else {
  echo "<script>window.location.replace('../mobiel/logout.php');</script>";
}
//endif;
 ?>
