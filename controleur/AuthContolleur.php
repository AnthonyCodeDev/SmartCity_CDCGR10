<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../modele/AuthModele.php';

class AuthControlleur {
    private $modele;

    public function __construct() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Constructeur de la classe AuthControlleur
        
        Arguments: aucun
        Return: string
        */
        $this->modele = new AuthModele();
    }

    public function verifierConnexion() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Vérifier la connexion
        
        Arguments: aucun
        Return: string
        */

        if (isset($_SESSION['utilisateur'])) {
            require_once __DIR__ . '/HomeControleur.php';
            $controleurHome = new HomeControleur();
            $controleurHome->afficherPage();
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['username']) || empty($_POST['motDePasse'])) {
                $_SESSION['error'] = "Veuillez remplir tous les champs.";
                header('Location: ' . BASE_URL);
                exit();
            }

            $username = htmlspecialchars(trim($_POST['username']));
            $motDePasse = htmlspecialchars(trim($_POST['motDePasse']));

            // Vérification LDAP
            $utilisateur = $this->modele->verifierUtilisateurLDAP($username, $motDePasse);

            if ($utilisateur) {
                // Stocker les informations utilisateur dans la session
                $_SESSION['utilisateur'] = $utilisateur;

                header('Location: ' . BASE_URL);
                exit();
            } else {
                $_SESSION['error'] = "Nom d'utilisateur ou mot de passe incorrect.";
                // header('Location: ' . BASE_URL);
                // exit();
            }
        }

        $this->afficherPage();
    }

    public function afficherPage() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Afficher la page
        
        Arguments: aucun
        Return: vue
        */

        require __DIR__ . '/../vue/auth.php';
    }
}

// Exécuter le contrôleur
$controleur = new AuthControlleur();
$controleur->verifierConnexion();
