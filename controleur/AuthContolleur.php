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
            // Vérifie si les champs sont remplis
            if (empty($_POST['email']) || empty($_POST['motDePasse'])) {
                $_SESSION['error'] = "Veuillez remplir tous les champs.";
                header('Location: ' . BASE_URL);
                exit();
            }

            $email = trim($_POST['email']);
            $motDePasse = trim($_POST['motDePasse']);

            // Récupération de l'utilisateur
            $utilisateur = $this->modele->recupererUtilisateurLDAP($email, $motDePasse);

            if ($utilisateur) {
                // Stocke l'utilisateur dans la session
                $_SESSION['utilisateur'] = [
                    'nom' => $utilisateur['nom'],
                    'prenom' => $utilisateur['prenom'],
                    'email' => $utilisateur['email']
                ];

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
