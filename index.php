<?php
$csvFilename = "../products.csv";
$csvData = file_get_contents($csvFilename);
$csvDelimiter = ",";

$csvRows = str_getcsv($csvData, "\n");
foreach($csvRows as &$row)
    $row = str_getcsv($row, $csvDelimiter);

$pairings = [
    'name' => 0,
    'link' => 1,
    'img' => 2,
    'count_rates' => 3
];
$i = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eStoreMedia - Task 2</title>
</head>
<body>
    <table>
        <?php
            foreach ($csvRows as $line) {
                echo "<tr>";
                    echo "<td>". $i ."</td>";

                foreach($pairings as $key => $col) {
                    $productHref = parse_url($line[1], PHP_URL_QUERY);
                    if($line[$col] == $line[0]) {
                        echo "<td><a href='product.php?" . $productHref . "'>" . $line[0] . "</a></td>";
                    } else if($line[$col] == $line[1]) {
                        echo "<td><a href='product.php?" . $productHref . "'>" . $line[1] . "</a></td>";
                    } else {
                        echo "<td>".$line[$col]."</td>";
                    }
                }
                $i++;
                echo "</tr>\n";
            }
        ?>
    </table>
</body>
</html>
<?php
$productId = trim($line[1], "http://estoremedia.space/DataIT/product.php?id=");
if (isset($_GET["id"])) {
    include_once("product.php");
}