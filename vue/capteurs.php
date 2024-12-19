<?php require_once __DIR__ . '/header.php'; ?>

<main class="smartcity-main-container">
    <h1 class="smartcity-title">Liste des capteurs</h1>
    <div class="smartcity-slogan">Voici les capteurs installés, actifs et inactifs.</div>
</main>
<section class="smartcity-incidents">
    <!-- Capteurs solaires -->
    <div class="smartcity-incidents-container">
        Capteurs Solaires
    </div>
    <?php if (!empty($capteursSolaire)): ?>
        <?php foreach ($capteursSolaire as $capteur): ?>
            <?php
                $etatCapteur = $capteur['StateUp'] ? 'success' : 'danger';
            ?>
            <div class="smartcity-incidents-alert <?= $etatCapteur; ?>">
                <div class="smartcity-incidents-alert-content">
                    <div class="smartcity-incidents-alert-title">
                        <?= htmlspecialchars($capteur['Name']); ?> (<?= $capteur['IPv4']; ?>)
                    </div>
                    <div class="smartcity-incidents-alert-date">
                        Ajouté le : <?= htmlspecialchars($capteur['DateAdded']); ?>
                    </div>
                    <div class="smartcity-incidents-actions" style="margin: 10px 0;">
                        <?php if ($capteur['StateUp']): ?>
                            <a href="capteurs?action=stop-sensor&id=<?= urlencode($capteur['IPv4']); ?>" 
                               class="smartcity-incidents-alert-btn" 
                               onclick="return confirm('Voulez-vous vraiment désactiver ce capteur ?');">
                                🛑 Éteindre
                            </a>
                        <?php else: ?>
                            <a href="capteurs?action=start-sensor&id=<?= urlencode($capteur['IPv4']); ?>" 
                               class="smartcity-incidents-alert-btn" 
                               onclick="return confirm('Voulez-vous vraiment allumer ce capteur ?');">
                                🟢 Démarrer
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="smartcity-incidents-description">
                    <?= htmlspecialchars($capteur['nombre_donnees']); ?> mesures enregistrées.<br>
                    Production dernières 6 heures : <?= number_format(htmlspecialchars($capteur['production_6h']), 2); ?> kWh
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun capteur solaire à afficher.</p>
    <?php endif; ?>

    <!-- Capteurs éoliens -->
    <div class="smartcity-incidents-container">
        Capteurs Éoliens
    </div>
    <?php if (!empty($capteursEolien)): ?>
        <?php foreach ($capteursEolien as $capteur): ?>
            <?php
                $etatCapteur = $capteur['StateUp'] ? 'success' : 'danger';
            ?>
            <div class="smartcity-incidents-alert <?= $etatCapteur; ?>">
                <div class="smartcity-incidents-alert-content">
                    <div class="smartcity-incidents-alert-title word-break">
                        <?= htmlspecialchars($capteur['Name']); ?> (<?= $capteur['IPv4']; ?>)
                    </div>
                    <div class="smartcity-incidents-alert-date">
                        Ajouté le : <?= htmlspecialchars($capteur['DateAdded']); ?>
                    </div>
                    <div class="smartcity-incidents-actions" style="margin: 10px 0;">
                        <?php if ($capteur['StateUp']): ?>
                            <a href="capteurs?action=stop-sensor&id=<?= urlencode($capteur['IPv4']); ?>" 
                               class="smartcity-incidents-alert-btn" 
                               onclick="return confirm('Voulez-vous vraiment désactiver ce capteur ?');">
                                🛑 Éteindre
                            </a>
                        <?php else: ?>
                            <a href="capteurs?action=start-sensor&id=<?= urlencode($capteur['IPv4']); ?>" 
                               class="smartcity-incidents-alert-btn" 
                               onclick="return confirm('Voulez-vous vraiment allumer ce capteur ?');">
                                🟢 Démarrer
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="smartcity-incidents-description">
                    <?= htmlspecialchars($capteur['nombre_donnees']); ?> mesures enregistrées.<br>
                    Production dernières 6 heures : <?= number_format(htmlspecialchars($capteur['production_6h']), 2); ?> kWh
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun capteur éolien à afficher.</p>
    <?php endif; ?>
</section>

</body>
</html>
