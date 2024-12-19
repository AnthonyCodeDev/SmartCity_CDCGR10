<?php

require_once __DIR__ . '/config.php';
// Informations de connexion
if ($productionMode == false) {
    // DEV MODE
    $host = 'localhost';
    $dbname = 'db_smartcity_energie';
    $username = 'root';
    $password = '';
} else {
    // PROD MODE
    $host = 'localhost';
    $dbname = 'db_smartcity_energie';
    $username = 'root';
    $password = 'HEH2/16';
}

// Options PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Affiche les erreurs SQL
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retourne les résultats sous forme associative
    PDO::ATTR_EMULATE_PREPARES => false, // Utilise les requêtes préparées réelles
];

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, $options);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
