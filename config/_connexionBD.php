<?php
// Informations de connexion
$host = 'localhost';
$dbname = 'smartcity_db';
$username = 'root';
$password = '';

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
