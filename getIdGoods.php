<?php
set_time_limit (0);
$q = $_GET['q'];
$cat = $_GET['cat'];
$p = $_GET['p'];
$resCat = $_GET['resCat'];


function getId($q, $p, $cat, $resCat) {

    require_once 'db.php';
    $pdo->query('CREATE TABLE IF NOT EXISTS '.$cat.'(id INT NOT NULL AUTO_INCREMENT, idGood VARCHAR (255) NOT NULL, PRIMARY KEY (ID) );');


    $result = file_get_contents('http://app.yiwugo.com/product/2016product/alllisttest.htm?password=dbqqbq@gmail.com&q='.$q.'&cpage='.$p);
    $result = json_decode($result);
    $result = (array) $result;
    
    if($result == 0 || $p == 101) {
        echo '<script>location.href = "http://localhost/createTable.php?id=1&cat='.$cat.'&resCat='.$resCat.'"</script>';
    }
    
    foreach($result['prslist'] as $k => $v) {
        $v = (array) $v;
        $pdo->query('INSERT INTO '.$cat.' (idGood) VALUES ("'.$v['id'].'");');
    }

    $p = ++$p;
    echo '<script>location.href = "http://localhost/getIdGoods.php?q='.$q.'&p='.$p.'&cat='.$cat.'&resCat='.$resCat.'"</script>';
}

getId($q, $p, $cat, $resCat);









