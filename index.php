<?php
    require_once 'vendor/autoload.php';
    require_once 'XmlData.php';

    $database = new Dibi\Connection([
        'driver'   => 'mysqli',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'database' => 'pohovor',
    ]);

    $file = "data/xml.xml";
    $values = array();

    $xmlData = new \Xml\XmlData($database);

    $xmlData->installTableZaznamy();
    $xml = $xmlData->loadDataFromXml($file);

    foreach ($xml as $row) {
        $firstName = (string)$row->JMENO;
        $surName = (string)$row->PRIJMENI;
        $date = (string)$row->DATE;
        $date = date('Y-m-d', strtotime($date));

        $values = [
                'jmeno' => $firstName,
                'prijmeni' => $surName,
                'datum' => $date
        ];

        $xmlData->instertDataIntoTableZaznamy($values);
        //$database->query('INSERT INTO `zaznamy` %v', $values);
    }

    $records = $xmlData->getDataFromTableZaznamy();

?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <title>Pohovor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/main.js"></script>
</head>

<body>
    <table style="width:100%">
        <th>ID</th>
        <th>Jméno</th>
        <th>Příjmení</th>
        <th>Datum</th>
            <?php
                foreach ($records as $row) {
                    echo '<tr>';
                    echo '<td>' . $row->id . '</td>';
                    echo '<td>' . $row->jmeno . '</td>';
                    echo '<td>' . $row->prijmeni . '</td>';
                    echo '<td>' . date("d.m.Y", strtotime($row->datum)) . '</td>';
                    echo '</tr>';
                }
            ?>
    </table>


</body>
</html>