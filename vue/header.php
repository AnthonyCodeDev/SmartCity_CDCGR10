
<?php

require_once __DIR__ . '/../config/config.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCity - Suivi de consommation solaire & éolien</title>
    <link rel="stylesheet" href="assets/css/reset_css.css">
    <link rel="stylesheet" href="assets/css/web.css">
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon">
    <script src="assets/js/web.js" defer></script>
    <base href="<?php echo BASE_URL; ?>">
    <script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    </script>
</head>
<body>
    <header>
        <nav class="smartcity-navbar">
            <a href="<?php echo BASE_URL; ?>" class="smartcity-navbar-item-one">
                <img src="assets/images/logo.png" alt="Logo du siteweb SmartCity" class="smartcity-logo">
                <span>SmartCity -</span> Suivi de consommation solaire & éolien
            </a>
            <div class="smartcity-navbar-item-two">
                <a href="#consommation">Consommation</a>
                <a href="#productionsolaire">Production solaire</a>
                <a href="incidents">Incidents</a>
                <a href="capteurs">Capteurs</a>
                <a href="deconnexion">Déconnexion</a>
            </div>
        </nav>
    </header>
