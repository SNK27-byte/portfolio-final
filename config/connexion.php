<?php

$httpHost = strtolower((string) preg_replace('/:\d+$/', '', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''));
$isProduction = strpos($httpHost, 'leroypaul.be') !== false;

if ($isProduction) {
    $dbHost = 'localhost';
    $dbName = 'zune9543_portfolio';
    $dbUser = 'zune9543_admin';
    $dbPass = 'Epse936H4';
} else {
    $dbHost = 'localhost';
    $dbName = 'Portfolio';
    $dbUser = 'root';
    $dbPass = '';
}

try {
    $bdd = new PDO(
        'mysql:host=' . $dbHost . ';dbname=' . $dbName . ';charset=utf8',
        $dbUser,
        $dbPass,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
} catch (Exception $e) {
    die('Erreur: ' . $e->getMessage());
}
