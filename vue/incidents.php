<?php require_once __DIR__ . '/header.php'; ?>

<main class="smartcity-main-container">
    <a href="incidents"><h1 class="smartcity-title">Derniers incidents</h1></a>
    <div class="smartcity-slogan"><?= $description; ?></div>
</main>

<section class="smartcity-incidents">
    <?php if (isset($_SESSION["utilisateur"]) && $_SESSION["utilisateur"]["role"] == "admin") { ?>
    <a href="incidents?action=create-incident">
        <div class="smartcity-create-incident">
            Créer un incident
        </div>
    </a>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="smartcity-error">
            <?= htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>


    
    <?php

        // Détermine si on est en mode édition ou création
        $isEditMode = isset($_GET['action']) && ($_GET['action'] === 'edit-incident') && isset($incidentEdit);
        $isCreateMode = isset($_GET['action']) && $_GET['action'] === 'create-incident';

        if ($isEditMode || $isCreateMode):
        ?>
            <!-- Formulaire pour créer ou modifier un incident -->
            <form method="POST" action="incidents?action=<?= $isEditMode ? 'update-incident' : 'store-incident'; ?>" class="smartcity-form">
                <?php if ($isEditMode): ?>
                    <input type="hidden" name="id" value="<?= $incidentEdit['ID_incident']; ?>">
                <?php endif; ?>

                <div class="smartcity-form-group">
                    <label for="nom" class="smartcity-form-label">Nom :</label>
                    <input type="text" id="nom" name="nom" class="smartcity-form-input"
                        value="<?= $isEditMode ? htmlspecialchars($incidentEdit['nom']) : ''; ?>"
                        placeholder="Entrez le nom de l'incident" required minlength="3" maxlength="100">
                </div>

                <div class="smartcity-form-group">
                    <label for="description" class="smartcity-form-label">Description :</label>
                    <textarea minlength="12" maxlength="100" id="description" name="description" class="smartcity-form-textarea" placeholder="Décrivez l'incident" required><?= $isEditMode ? htmlspecialchars($incidentEdit['description']) : ''; ?></textarea>
                </div>

                <div class="smartcity-form-group">
                    <label for="niveau" class="smartcity-form-label">Niveau :</label>
                    <select id="niveau" name="niveau" class="smartcity-form-select" required>
                        <option value="1" <?= $isEditMode && $incidentEdit['niveauPriorite'] == 1 ? 'selected' : ''; ?>>Résolu</option>
                        <option value="2" <?= $isEditMode && $incidentEdit['niveauPriorite'] == 2 ? 'selected' : ''; ?>>Warning</option>
                        <option value="3" <?= $isEditMode && $incidentEdit['niveauPriorite'] == 3 ? 'selected' : ''; ?>>Danger</option>
                    </select>
                </div>
                <div class="smartcity-form-actions">
                    <button type="submit" class="smartcity-form-button">
                        <?= $isEditMode ? 'Mettre à jour' : 'Créer'; ?>
                    </button>
                </div>
            </form>
        <?php endif; ?>
<?php } ?>
<?php
function getNiveauTexte($niveau) {
    $niveaux = [1 => "success", 2 => "warning", 3 => "danger"];
    return $niveaux[$niveau] ?? "Inconnu";
}
?>

<?php if (!empty($recupererDerniersIncidents)): ?>
    <?php foreach ($recupererDerniersIncidents as $incident): ?>
    <div class="smartcity-incidents-alert <?= getNiveauTexte($incident['niveauPriorite']); ?>">
        <div class="smartcity-incidents-alert-content">
            <div class="smartcity-incidents-alert-icon">
                <div class="smartcity-incidents-alert-bubble">&nbsp;</div>
                <div class="smartcity-incidents-alert-title">
                    <?php echo htmlspecialchars($incident['nom']); ?>
                </div>
            </div>
            <div class="smartcity-incidents-alert-date">
                <?php echo htmlspecialchars($incident['date_creation']); ?>
                <?php if (isset($_SESSION["utilisateur"]) && $_SESSION["utilisateur"]["role"] == "admin") { ?>
                <a href="incidents?action=delete-incident&id=<?= $incident['ID_incident']; ?>" 
                    onclick="return confirm('Voulez-vous vraiment supprimer cet incident ?');">
                    🗑️
                </a>
                <a href="incidents?action=edit-incident&id=<?= $incident['ID_incident']; ?>">
                    ✏️
                </a>
                <?php } ?>
            </div>
        </div>
        <div class="smartcity-incidents-description">
            <?php echo htmlspecialchars($incident['description']); ?>
        </div>
    </div>
<?php endforeach; ?>


<?php else: ?>
    <p>Tout va bien ! Aucun incident à afficher. ⭐</p>
<?php endif; ?>

</section>

<?php if (count($recupererAlertesProduction) > 0) { ?>
<hr class="smartcity-incidents-hr">
<section class="smartcity-incidents">
<div class="smartcity-incidents-container">
    Alertes de surcharge
</div>
<?php
foreach ($recupererAlertesProduction as $alerte) {
    ?>
    <div class="smartcity-incidents-alert warning">
        <div class="smartcity-incidents-alert-content">
            <div class="smartcity-incidents-alert-icon">
                <div class="smartcity-incidents-alert-bubble">&nbsp;</div>
                <div class="smartcity-incidents-alert-title">
                    Alerte Surcharge <?= strtoupper($alerte['niveau']); ?>
                </div>
            </div>
            <div class="smartcity-incidents-alert-date">
                <?= $alerte['date_signalement']; ?>
            </div>
        </div>
        <div class="smartcity-incidents-description">
            <?= $alerte['description']; ?>
        </div>
    </div>
    <?php
}
?>
</section>
<?php } ?>


</body>
</html>
