<?php

// Gestion des erreurs de connexion
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="assets/css/auth.css">
    <script>
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
</script>
</head>
<body>
    <div class="auth-container">
        <h1 class="auth-title">Connexion</h1>
        
        <?php if (!empty($error)): ?>
            <div class="auth-error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form action="<?= BASE_URL ?>" method="POST" class="auth-form">
            <div class="auth-form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" class="auth-input" required>
            </div>

            <div class="auth-form-group">
                <label for="motDePasse">Mot de passe :</label>
                <input type="password" id="motDePasse" name="motDePasse" class="auth-input" required minlength="6">
            </div>

            <div class="auth-form-actions">
                <button type="submit" class="auth-button">Se connecter</button>
            </div>
        </form>
    </div>
</body>
</html>
