<?php require_once __DIR__ . '/header.php'; ?>

<main class="smartcity-main-container">
    <h1 class="smartcity-title">Générer un rapport de complet</h1>
    <div class="smartcity-slogan">Les rapports sont générés tous les jours à 00:00 UTC+1.</div>
</main>

<section id="consommation" class="smartcity-conso">
    <div class="smartcity-conso-container smartcity-conso-container-blue">
        <div class="smartcity-conso-title">
            <?= htmlspecialchars($recupererInformationsGlobales['consommation']); ?>
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
            <?= htmlspecialchars($recupererInformationsGlobales['productionSolaire']); ?>
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
            <?= htmlspecialchars($recupererInformationsGlobales['productionEolienne']); ?>
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
    <a class="smartcity-container" href="getRapport-<?= date('d-m-Y-H-i'); ?>.pdf">
        <div class="smartcity-button">Créer un rapport instantané</div>
    </a>
    <div class="smartcity-description">La génération du rapport peut prendre jusqu’a 1 minute.</div>
</main>

<section class="smartcity-incidents">
    <div class="smartcity-incidents-inner">
        <div class="smartcity-incidents-container">
            Rapports de SmartCity
        </div>
        <?php 
        if (count($recupererRapports) > 0) {
            foreach ($recupererRapports as $rapport) { ?>
            <a href="<?= htmlspecialchars($rapport['chemin_access']); ?>">
                <div class="smartcity-incidents-alert success">
                    <div class="smartcity-incidents-alert-content">
                        <div class="smartcity-incidents-alert-icon">
                            <div class="smartcity-incidents-alert-bubble">
                                &nbsp;
                            </div>
                            <div class="smartcity-incidents-alert-title">
                                Rapport <?= $rapport['rapport_type'] == 1 ? 'automatique' : 'manuel'; ?>
                            </div>
                        </div>
                    </div>
                    <div class="smartcity-incidents-description">
                        <?php
                        $dateRapport = substr($rapport['chemin_access'], 14, 16); // Exemple : '19-12-2024-14-54'
                        $dateParts = explode('-', $dateRapport); // Découpage en ['19', '12', '2024', '14', '54']
                        $formattedDate = sprintf('%s-%s-%s %s:%s', $dateParts[2], $dateParts[1], $dateParts[0], $dateParts[3], $dateParts[4]);
                        $dateRapport = date('d-m-Y H:i', strtotime($formattedDate));
                        ?>
                        Accéder au rapport <?= $rapport['rapport_type'] == 1 ? 'automatique' : 'manuel'; ?> du <?= $dateRapport; ?>
                    </div>

                </div>
            </a>
            <?php } ?>
        <?php } else { ?>
            <div class="smartcity-incidents-alert error">
                <div class="smartcity-incidents-alert-content">
                    <div class="smartcity-incidents-alert-icon">
                        <div class="smartcity-incidents-alert-bubble">
                            &nbsp;
                        </div>
                        <div class="smartcity-incidents-alert-title">
                            Aucun rapport
                        </div>
                    </div>
                </div>
                <div class="smartcity-incidents-description">
                    Aucun rapport n'a été généré pour le moment.
                </div>
            </div>
        <?php } ?>
    </div>
</section>
</body>
</html>
