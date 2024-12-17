<?php require_once __DIR__ . '/header.php'; ?>

<main class="smartcity-main-container">
    <h1 class="smartcity-title"><?= $welcomeMessage; ?></h1>
    <div class="smartcity-slogan">Votre ville, connectée pour aujourd'hui et pensée pour demain.</div>
    <div class="smartcity-description">Surveillez en temps réel votre consommation et votre production d’énergie. Optimisez vos ressources pour un avenir plus durable. ⚡</div>
    <div class="smartcity-btn-container">
        <a class="smartcity-container" href="genererrapport">
            <div class="smartcity-button">Générer un rapport complet</div>
        </a>
    </div>
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

</body>
</html>
