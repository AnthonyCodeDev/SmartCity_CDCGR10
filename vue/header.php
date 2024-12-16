
<?php
define('BASE_URL', '/SmartCity_CDCGR10/');
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
                <a href=""><span>SmartCity -</span> Suivi de consommation solaire & éolien</a>
            </div>
            <div class="smartcity-navbar-item-two">
                <a href="<?php echo BASE_URL; ?>">Consommation</a>
                <a href="incidents">Production solaire</a>
                <a href="capteurs">Incidents</a>
                <a href="capteurs">Capteurs</a>
                <a href="capteurs"><img src="assets/images/user.png">&nbsp;Anthony</a>
            </div>
        </nav>
    </header>
