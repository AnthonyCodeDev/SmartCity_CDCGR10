<?php require_once __DIR__ . '/header.php'; ?>

<main class="smartcity-main-container">
    <h1 class="smartcity-title">Générer un rapport de complet</h1>
    <div class="smartcity-slogan">Les rapports sont générés tous les jours à 00:00 UTC+1.</div>
</main>

<section id="consommation" class="smartcity-conso">
    <div class="smartcity-conso-container smartcity-conso-container-blue">
        <div class="smartcity-conso-title">
            <?= $recupererInformationsGlobales['consommation']; ?>
        </div>
        <div class="smartcity-conso-iner">
            kWh
        </div>
        <div class="smartcity-conso-description">
            Consommation
        </div>
    </div>
    <div class="smartcity-conso-container smartcity-conso-container-orange">
        <div class="smartcity-conso-title">
            <?= $recupererInformationsGlobales['productionSolaire']; ?>
        </div>
        <div class="smartcity-conso-iner">
            kWh
        </div>
        <div class="smartcity-conso-description">
            Production solaire
        </div>
    </div>
    <div class="smartcity-conso-container smartcity-conso-container-green">
        <div class="smartcity-conso-title">
            <?= $recupererInformationsGlobales['productionEolienne']; ?>
        </div>
        <div class="smartcity-conso-iner">
            kWh
        </div>
        <div class="smartcity-conso-description">
            Production éolienne
        </div>
    </div> 
</section>
<div class="smartcity-graph-description">
    Statistiques globales des dernières 24 heures.
</div>

<main class="smartcity-rapport-container">
    <a class="smartcity-container" href="genererrapport">
        <div class="smartcity-button">Créer un rapport instantané</div>
    </a>
    <div class="smartcity-description">La génération du rapport peut prendre jusqu’a 1 minute.</div>
</main>

<section class="smartcity-incidents">
    <div class="smartcity-incidents-inner">
        <div class="smartcity-incidents-container">
            Rapports de SmartCity
        </div>
        <div class="smartcity-incidents-alert success">
            <div class="smartcity-incidents-alert-content">
                <div class="smartcity-incidents-alert-icon">
                    <div class="smartcity-incidents-alert-bubble">
                        &nbsp;
                    </div>
                    <div class="smartcity-incidents-alert-title">
                        Rapport automatique
                    </div>
                </div>
            </div>
            <div class="smartcity-incidents-description">
                Accéder au rapport automatique du 10/10/2021
            </div>
        </div>
        <div class="smartcity-incidents-alert success">
            <div class="smartcity-incidents-alert-content">
                <div class="smartcity-incidents-alert-icon">
                    <div class="smartcity-incidents-alert-bubble">
                        &nbsp;
                    </div>
                    <div class="smartcity-incidents-alert-title">
                        Rapport manuel
                    </div>
                </div>
            </div>
            <div class="smartcity-incidents-description">
                Accéder au rapport manuel du 10/10/2021
            </div>
        </div>
    </div>
</section>
</body>
</html>
