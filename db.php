<?php

$config = [
    'host' => 'localhost:3306',
    'db_name' => 'db',
    'db_user' => 'root',
    'db_pass' => '',
];

// Данные для подключения к базе данных
$driver = 'mysql';
$host = $config['host'].':3306';
$db_name = $config['db_name'];
$db_user = $config['db_user'];
$db_pass = $config['db_pass'];
$charset = 'utf8';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try{
    // Создание обьекта PDO и передача данных
    // для подключения в конструктор класса
    $pdo = new PDO(
                    "$driver:host=$host;
                    dbname=$db_name;
                    charset=$charset",
                    $db_user,
                    $db_pass,
                    $options
                );
// Отладка ошибок подключения
}catch(PDOException $e) {
    die('Ошибка подключения к базе данных'.$e);
}

// function query($sql, $pdo) {
//     $result = $pdo->query($sql);
//     return $result;
// }

// function row($sql, $pdo) {
//     $result = $pdo->query($sql);
//     return $result->fetchAll(PDO::FETCH_ASSOC);
// }

// function column($sql, $pdo) {
//     $result = $pdo->query($sql);
//     return $result->fetchColumn(PDO::FETCH_ASSOC);
// }



    