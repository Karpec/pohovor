<?php
    require_once 'vendor/autoload.php';

    $database = new Dibi\Connection([
        'driver'   => 'mysqli',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'database' => 'pohovor',
    ]);

    //Vytvoření tabulky
    $database->query('
        CREATE TABLE IF NOT EXISTS `zaznamy` (
        id INT(10) NOT NULL AUTO_INCREMENT,
        jmeno VARCHAR(64) NOT NULL,
        prijmeni VARCHAR(64) NOT NULL,
        datum DATE,
        PRIMARY KEY (id))
    ');

    $xml = simplexml_load_file('data/xml.xml');

    foreach ($xml->children() as $row) {
        $firstName = (string)$row->JMENO;
        $surName = (string)$row->PRIJMENI;
        $date = (string)$row->DATE;
        $date = date('Y-m-d', strtotime($date));

        $values = [
                'jmeno' => $firstName,
                'prijmeni' => $surName,
                'datum' => $date
        ];
        $database->query('INSERT INTO `zaznamy` %v', $values);
    }

    $records = $database->query('
        SELECT id, jmeno, prijmeni, datum 
        FROM `zaznamy`
        ORDER BY datum
    ');

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