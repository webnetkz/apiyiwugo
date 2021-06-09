<?php
set_time_limit (0);
$_GET['p'] = 1;
$cat = $_GET['cat'];
$id = $_GET['id'];
require_once 'db.php';

$resCat = $_GET['resCat'];
$oneCat = 'Обувь и чулочно-носочные изделия';

$res = $pdo->query('SELECT idGood FROM '.$cat.' WHERE id = '.$id);
$res = $res->fetchAll(PDO::FETCH_ASSOC);
$res = $res[0]['idGood'];

if($res == NULL) {
    exit('Success');
}


$url = 'http://app.yiwugo.com/product/2016product/onetest.htm?password=dbqqbq@gmail.com&key='.$res;

$curlSession = curl_init();
curl_setopt($curlSession, CURLOPT_URL, $url);
curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

$jsonData = curl_exec($curlSession);
curl_close($curlSession);

// $result = file_get_contents($url);
$result = [];
array_push($result, $jsonData);
$result = json_decode($result[0]);
$v = (array) $result;

// var_dump($v);
// exit();


$shop = (array) $v['shopinfo'];
$shop = (array) $shop['shop'];
$detail = (array) $v['detail'];
$detail = (array) $detail['productDetailVO'];
$img = (array) $v['detail'];
$img = (array) $img['sdiProductsPicList'];
$price = (array) $v['detail'];
$price = (array) $price['sdiProductsPriceList'];

$realPrice = $detail['sellPrice'];
$realPrice = substr($realPrice, 0, -1);
$x = substr($realPrice, -1);
$realPrice = substr($realPrice, 0, -1).'.'.$x;


$i;
foreach($img as $k => $v) {
    $v = (array) $v;
    $i .= 'http://img1.yiwugo.com/'.$v['picture1'].'///';
}
$i = substr($i, 0, -3);

$mainPrice;
foreach($price as $k => $v) {
    $v = (array) $v;
    $v['conferPrice'] = substr($v['conferPrice'], 0, -1);
    $x = substr($v['conferPrice'], -1);
    $v['conferPrice'] = substr($v['conferPrice'], 0, -1).'.'.$x;

    $mainPrice .= '"'.$res.'";"ru";"'.$v['conferPrice'].'";"'.$v['startNumber'].'";"Все"'."\r\n";
}

$email = $shop['shopId'].'@zavod.com';

if($realPrice >= 9999999) {
    $realPrice = 0;
}

    $detail['detaill'] = html_entity_decode($detail['detaill']);
    $detail['detaill'] = str_replace('"', '\'', $detail['detaill']);
    $detail['detaill'] = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    ', ';', '；'), '', $detail['detaill']);

$resVendors = '"'.$shop['shopName'].'";"en";"en";"'.$email.'";"'.$shop['mobile'].'";"'.$shop['marketinfo'].'<img src=\'http://img1.yiwugo.com/'.$shop['shopImageUrl'].'\'>";"Yiwu";"CN";"1";"'.$shop['factoryAddress'].' Name: '.$shop['contacter']. ' Phone: '.$shop['mobile']."\"\r\n";
$resGoods = '"'.$res."\";\"ru\";\"".$oneCat."///".$resCat."\";\"".$realPrice."\";\"9000000\";\"".$detail['startNumber']."\";\"".$detail['title']."\";\"".$detail['detaill']."\";\"".$shop['shopName']."\";\"".$i."\"\r\n";


$vendors = fopen('./tables/'.$cat.'/vendors.csv', 'a+');
$price = fopen('./tables/'.$cat.'/p.csv', 'a+');
$goods = fopen('./tables/'.$cat.'/g.csv', 'a+');

fwrite($vendors, $resVendors);
fwrite($price, $mainPrice);
fwrite($goods, $resGoods);


fclose($goods);
fclose($vendors);
fclose($price);

$id = ++$id;
if($id >= 5000) {
    exit('Success');
}
echo '<script>location.href="http://localhost/createTable.php?password=dbqqbq@gmail.com&id='.$id.'&cat='.$cat.'&resCat='.$resCat.'"</script>';