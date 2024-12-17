
<?php

require_once __DIR__ . '/../config/config.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCity - Suivi de consommation solaire & éolien</title>
    <link rel="stylesheet" href="assets/css/web.css">
    <script src="assets/js/web.js" defer></script>
    <base href="<?php echo BASE_URL; ?>">
</head>
<body>
    <header>
        <nav class="smartcity-navbar">
            <div class="smartcity-navbar-item-one">
                <a href="<?php echo BASE_URL; ?>"><span>SmartCity -</span> Suivi de consommation solaire & éolien</a>
            </div>
            <div class="smartcity-navbar-item-two">
                <a href="#consommation">Consommation</a>
                <a href="#productionsolaire">Production solaire</a>
                <a href="incidents">Incidents</a>
                <a href="capteurs">Capteurs</a>
                <a href="deconnexion">Déconnexion</a>
            </div>
        </nav>
    </header>
