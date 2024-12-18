<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../modele/AuthModele.php';

class AuthControlleur {
    private $modele;

    public function __construct() {
        $this->modele = new AuthModele();
    }

    public function verifierConnexion() {
        if (isset($_SESSION['utilisateur'])) {
            require_once __DIR__ . '/HomeControleur.php';
            $controleurHome = new HomeControleur();
            $controleurHome->afficherPage();
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['email']) || empty($_POST['motDePasse'])) {
                $_SESSION['error'] = "Veuillez remplir tous les champs.";
                header('Location: ' . BASE_URL);
                exit();
            }

            $email = htmlspecialchars(trim($_POST['email']));
            $motDePasse = htmlspecialchars(trim($_POST['motDePasse']));

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Adresse email invalide.";
                header('Location: ' . BASE_URL);
                exit();
            }

            // Vérification LDAP
            $utilisateur = $this->modele->verifierUtilisateurLDAP($email, $motDePasse);

            if ($utilisateur || True) {
                // Stocker les informations utilisateur dans la session
                $_SESSION['utilisateur'] = $utilisateur;

                header('Location: ' . BASE_URL);
                exit();
            } else {
                $_SESSION['error'] = "Email ou mot de passe incorrect.";
                header('Location: ' . BASE_URL);
                exit();
            }
        }

        $this->afficherPage();
    }

    public function afficherPage() {
        require __DIR__ . '/../vue/auth.php';
    }
}

// Exécuter le contrôleur
$controleur = new AuthControlleur();
$controleur->verifierConnexion();
