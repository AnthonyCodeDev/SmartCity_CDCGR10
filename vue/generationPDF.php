<?php require_once __DIR__ . '/header.php'; ?>
<link rel="stylesheet" type="text/css" media="screen" href="http://localhost/SmartCity_CDCGR10/assets/css/web.css" />
<style>
    .smartcity-navbar {
        display: none;
    }
    .smartcity-main-container {
        margin: 0 auto;
        padding: 0 15px;
        text-align: center;
    }
    .smartcity-table-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px 0;
    }
    .smartcity-table {
        border-collapse: collapse; /* Supprime les espaces entre les cellules */
        text-align: center;
    }
    .smartcity-table td {
        width: 200px; /* Taille fixe pour chaque bloc */
        height: 200px;
        padding: 20px;
        border: none; /* Supprime les bordures visibles */
        background-color: rgba(244, 244, 244, 0.2);
        border-radius: 10px; /* Coins arrondis */
        vertical-align: middle; /* Centre le contenu verticalement */
        text-align: center; /* Centre le contenu horizontalement */
    }
    .smartcity-table td .smartcity-conso-title {
        font-size: 24px;
        font-weight: bold;
    }
    .smartcity-table td .smartcity-conso-iner {
        font-size: 18px;
        margin: 10px 0;
    }
    .smartcity-table td .smartcity-conso-description {
        font-size: 14px;
        color: #555;
    }
</style>
<main class="smartcity-main-container">
    <h1 class="smartcity-title">Génération de rapport</h1>
    <h1 class="smartcity-title"><?= date('d-m-Y-H-i'); ?></h1>
    <div class="smartcity-description">Surveillez en temps réel votre consommation et votre production d’énergie. Optimisez vos ressources pour un avenir plus durable. ⚡</div>
</main>
<?php
// Exemple de données dynamiques récupérées depuis la base de données
$consommation = 120; // Exemple : consommation totale en kWh
$production_solaire = 250; // Exemple : production solaire en kWh
$production_eolienne = 180; // Exemple : production éolienne en kWh
?>

<div style="width: 100%; height: 150px; overflow: hidden;margin-top: 30px;display:flex;justify-content: center;border-radius:20px">
  <div style="float: left; width: 26.7%; height: 100%; background-color: #039DE0; text-align: center; color: white; padding: 20px; box-sizing: border-box;margin-right:10px;" class="smartcity-conso-container">
    <div style="font-size: 24px; font-weight: bold;"><?php echo $consommation; ?></div>
    <div style="font-size: 18px;">kWh</div>
    <div style="font-size: 14px;">Consommation</div>
  </div>
  <div style="float: left; width: 26.7%; height: 100%; background-color: #F9B759; text-align: center; color: white; padding: 20px; box-sizing: border-box;margin-right:10px" class="smartcity-conso-container">
    <div style="font-size: 24px; font-weight: bold;"><?php echo $production_solaire; ?></div>
    <div style="font-size: 18px;">kWh</div>
    <div style="font-size: 14px;">Production solaire</div>
  </div>
  <div style="float: left; width: 26.7%; height: 100%; background-color: #1E9E88; text-align: center; color: white; padding: 20px; box-sizing: border-box;" class="smartcity-conso-container">
    <div style="font-size: 24px; font-weight: bold;"><?php echo $production_eolienne; ?></div>
    <div style="font-size: 18px;">kWh</div>
    <div style="font-size: 14px;">Production éolienne</div>
  </div>
</div>


<div class="smartcity-graph-description" style="margin-top: 0;padding-top: 0;">
    Statistiques globales des dernières 24 heures.
</div>

</body>
</html>
