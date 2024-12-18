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
    <title>SmartCity - Authentification</title>
    <link rel="stylesheet" href="assets/css/reset_css.css">
    <link rel="stylesheet" href="assets/css/web.css">
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon">
    <style>
        body {
            background:#00081A;
            color:snow;
        }
    </style>
    <script>
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
</script>
</head>
<body>

    <div class="smartcity-auth-title">
        <span>SmartCity -</span> Suivi de connsommaiton solaire & éolien
    </div>

    <div class="login-container">
        <h1 class="login-title">
            <span class="emoji">👋</span> Je suis heureux de vous voir
            <div class="underline"></div>
        </h1>
        
        <?php if (!empty($error)): ?>
            <div class="auth-error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="<?= BASE_URL ?>" method="POST" class="login-form" autocomplete="off">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="username" id="username" name="username" placeholder="antho.ny" autocomplete="off">
            </div>

            <div class="form-group disableselect">
                <label for="password">Mot de passe</label>
                <div class="password-container">
                    <input type="password" id="motDePasse" name="motDePasse" placeholder="Mot de passe">
                    <span class="toggle-password" id="togglePassword">👁️</span>
                </div>
            </div>

            <button type="submit" class="login-button">Se connecter</button>
        </form>
    </div>
    <script>
        // Sélection des éléments
        const passwordInput = document.getElementById('motDePasse');
        const togglePassword = document.getElementById('togglePassword');

        // Ajout d'un événement pour basculer l'affichage
        togglePassword.addEventListener('click', () => {
            // Vérifie le type actuel du champ mot de passe
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Change l'icône en fonction de l'état
            togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>


</body>
</html>
