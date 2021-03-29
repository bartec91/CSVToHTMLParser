<?php
include "inc/simple_html_dom.php";

$mainUrl = "http://estoremedia.space/DataIT/product.php?id=";
$prodCardId = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
$url = $mainUrl . $prodCardId;

$htmlProductCard = file_get_html($url);
foreach($htmlProductCard->find("div.col-lg-9") as $p) {
    $product = array();
    $product['json'] = $p->find('div.card div.card-body script')[0]->innertext;
    $productJSON = json_decode($product['json'], true);
    $product['name'] = $p->find('div.row h3')[0]->innertext;
    $product['img'] = $p->find('div.card img.card-img-top')[0]->src;
    $product['price'] = $p->find('div.card div.card-body span')[0]->innertext;
    $product['price_old'] = isset($p->find('div.card div.card-body del')[0]->innertext) ? $p->find('div.card div.card-body del')[0]->innertext : NULL;
    $count_rates = preg_match('#\((.*?)\)#', $p->find('div.card-footer small')[0]->innertext, $match);
    $product['count_rates'] = $match[1];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Card</title>
</head>
<body>
    <?php
        if(isset($productJSON['products']['variants'])) {
            foreach($productJSON['products']['variants'] as $variantKey => $variantValue) {
        ?>
            <h3><?php echo $product['name'] . " #" . $variantKey; ?></h3>
            <h5>Current Price Variant:</h5>
            <span><?php echo $variantValue['price']; ?></span>
            <h5>Old Price Variant:</h5>
            <?php
                if(isset($variantValue['price_old'])) {
                    ?><del><?php echo $variantValue['price_old']; ?></del><?php
                }
            ?>
        <?php
            }
        }
    ?>
    <h5>Current Price:</h5>
    <span><?php echo $product['price']; ?></span>
    <?php
        if(isset($product['price_old'])) {
            ?><h5>Old Price:</h5>
            <del><?php echo $product['price_old']; ?></del><?php
        }
    ?>
    <h5>IMAGE URL:</h5>
    <a href="<?php echo $product['img']; ?>"><?php echo $product['img']; ?></a>
    <h5>Product Code:</h5>
    <p><?php echo $productJSON['products']['code']; ?></p>
    <h5>Count Rates:</h5>
    <p><?php echo $product['count_rates']; ?></p>
</body>
</html>