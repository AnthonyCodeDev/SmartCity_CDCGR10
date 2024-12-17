<?php require_once __DIR__ . '/header.php'; ?>

<main class="smartcity-main-container">
    <h1 class="smartcity-title">Liste des capteurs</h1>
    <div class="smartcity-slogan">Voici les capteurs installés, actifs et inactif.</div>
</main>

<section class="smartcity-incidents">
    <div class="smartcity-incidents-inner">
        <div class="smartcity-incidents-container">
            Capteurs solaires
        </div>
        <div class="smartcity-incidents-alert success">
            <div class="smartcity-incidents-alert-content">
                <div class="smartcity-incidents-alert-icon">
                    <div class="smartcity-incidents-alert-bubble">
                        &nbsp;
                    </div>
                    <div class="smartcity-incidents-alert-title">
                        Capteur Solaire #1
                    </div>
                </div>
            </div>
            <div class="smartcity-incidents-description">
            192.168.1.1 est actif. Les dernières 6 heures, ce capteur à produit 84.25 kWh.
            </div>
        </div>
        <div class="smartcity-incidents-alert success">
            <div class="smartcity-incidents-alert-content">
                <div class="smartcity-incidents-alert-icon">
                    <div class="smartcity-incidents-alert-bubble">
                        &nbsp;
                    </div>
                    <div class="smartcity-incidents-alert-title">
                        Capteur Solaire #2
                    </div>
                </div>
            </div>
            <div class="smartcity-incidents-description">
            192.168.1.1 est actif. Les dernières 6 heures, ce capteur à produit 9.25 kWh.
            </div>
        </div>
        <div class="smartcity-incidents-alert danger">
            <div class="smartcity-incidents-alert-content">
                <div class="smartcity-incidents-alert-icon">
                    <div class="smartcity-incidents-alert-bubble">
                        &nbsp;
                    </div>
                    <div class="smartcity-incidents-alert-title">
                        Capteur Solaire #3
                    </div>
                </div>
            </div>
            <div class="smartcity-incidents-description">
            Un capteur solaire 192.168.1.1 ne répond plus. Les dernières 6 heures, ce capteur à produit 2.26 kWh.
            </div>
        </div>
    </div>
    <div class="smartcity-incidents-inner">
        <div class="smartcity-incidents-container">
            Capteurs éolien
        </div>
        <div class="smartcity-incidents-alert success">
            <div class="smartcity-incidents-alert-content">
                <div class="smartcity-incidents-alert-icon">
                    <div class="smartcity-incidents-alert-bubble">
                        &nbsp;
                    </div>
                    <div class="smartcity-incidents-alert-title">
                        Capteur Éolien #1
                    </div>
                </div>
            </div>
            <div class="smartcity-incidents-description">
            192.168.1.1 est actif. Les dernières 6 heures, ce capteur à produit 2.56 kWh.
            </div>
        </div>
        <div class="smartcity-incidents-alert success">
            <div class="smartcity-incidents-alert-content">
                <div class="smartcity-incidents-alert-icon">
                    <div class="smartcity-incidents-alert-bubble">
                        &nbsp;
                    </div>
                    <div class="smartcity-incidents-alert-title">
                        Capteur Éolien #2
                    </div>
                </div>
            </div>
            <div class="smartcity-incidents-description">
            192.168.1.1 est actif. Les dernières 6 heures, ce capteur à produit 23.45 kWh.
            </div>
        </div>
        <div class="smartcity-incidents-alert danger">
            <div class="smartcity-incidents-alert-content">
                <div class="smartcity-incidents-alert-icon">
                    <div class="smartcity-incidents-alert-bubble">
                        &nbsp;
                    </div>
                    <div class="smartcity-incidents-alert-title">
                        Capteur Éolien #3
                    </div>
                </div>
            </div>
            <div class="smartcity-incidents-description">
                Un capteur solaire 192.168.1.1 ne répond plus. Les dernières 6 heures, ce capteur à produit 2.5 kWh.
            </div>
        </div>
    </div>
</section>
</body>
</html>
