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
<section class="smartcity-conso">
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
    Statistiques des derniers 30 jours.
</div>

<section class="smartcity-graph" id="consommation">
    <div class="chart-container">
        <div class="chart-container-title">
            Consommation d'énergie
        </div>
        <canvas id="consommationChart"></canvas>
    </div>
</section>

<section class="smartcity-graph" id="productionsolaire">
    <div class="chart-container">
        <div class="chart-container-title">
            Production d'énergie : Solaire & Éolienne
        </div>
        <canvas id="productionChart"></canvas>
    </div>
</section>

    <script src="assets/js/chart.js"></script>

    <script>
        // Récupère les données de PHP
        const labels = <?= json_encode(array_column($production30Jours, 'jour')); ?>;
        const consommationData = <?= json_encode(array_column($consommation30Jours, 'total')); ?>;
        const productionSolaire = <?= json_encode(array_column($production30Jours, 'solaire')); ?>;
        const productionEolienne = <?= json_encode(array_column($production30Jours, 'eolienne')); ?>;

        // ---------- Graphique Consommation ----------
        const ctxConsommation = document.getElementById('consommationChart').getContext('2d');
        new Chart(ctxConsommation, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Consommation quotidienne',
                    data: consommationData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: { y: { beginAtZero: true } }
            }
        });

        // ---------- Graphique Production ----------
        const ctxProduction = document.getElementById('productionChart').getContext('2d');
        new Chart(ctxProduction, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Production Solaire (kWh)',
                        data: productionSolaire,
                        borderColor: 'rgba(255, 206, 86, 1)',
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderWidth: 2,
                        tension: 0.4
                    },
                    {
                        label: 'Production Éolienne (kWh)',
                        data: productionEolienne,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>

</body>
</html>
