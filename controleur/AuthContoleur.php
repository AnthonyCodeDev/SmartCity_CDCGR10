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






// // Test 1 : Test du constructeur
// echo "Test 1 : Constructeur\n";
// $controleurTest = new AuthControlleur();
// if ($controleurTest) {
//     echo "PASS : Le contrôleur a été créé avec succès.\n";
// } else {
//     echo "FAIL : Le contrôleur n'a pas pu être créé.\n";
// }

// // Test 2 : Test de la fonction verifierConnexion avec session active
// echo "\nTest 2 : verifierConnexion avec session active\n";
// $_SESSION['utilisateur'] = ['username' => 'testuser'];
// ob_start();
// $controleurTest->verifierConnexion();
// $output = ob_get_clean();
// if (isset($_SESSION['utilisateur'])) {
//     echo "PASS : L'utilisateur est déjà connecté.\n";
// } else {
//     echo "FAIL : La connexion n'a pas été reconnue.\n";
// }

// // Test 3 : verifierConnexion avec données POST vides
// echo "\nTest 3 : verifierConnexion avec données POST vides\n";
// unset($_SESSION['utilisateur']);
// $_POST = ['username' => '', 'motDePasse' => ''];
// $_SERVER['REQUEST_METHOD'] = 'POST';
// ob_start();
// $controleurTest->verifierConnexion();
// $output = ob_get_clean();
// if ($_SESSION['error'] === "Veuillez remplir tous les champs.") {
//     echo "PASS : L'erreur pour les champs vides a été correctement détectée.\n";
// } else {
//     echo "FAIL : L'erreur pour les champs vides n'a pas été détectée.\n";
// }

// // Test 4 : verifierConnexion avec mauvais identifiants
// echo "\nTest 4 : verifierConnexion avec mauvais identifiants\n";
// $_POST = ['username' => 'wronguser', 'motDePasse' => 'wrongpassword'];
// $_SERVER['REQUEST_METHOD'] = 'POST';
// unset($_SESSION['error']);
// ob_start();
// $controleurTest->verifierConnexion();
// $output = ob_get_clean();
// if ($_SESSION['error'] === "Nom d'utilisateur ou mot de passe incorrect.") {
//     echo "PASS : L'erreur pour identifiants incorrects a été correctement détectée.\n";
// } else {
//     echo "FAIL : L'erreur pour identifiants incorrects n'a pas été détectée.\n";
// }

// // Test 5 : verifierConnexion avec bon identifiant (Mock LDAP)
// echo "\nTest 5 : verifierConnexion avec bon identifiant\n";
// $_POST = ['username' => 'validuser', 'motDePasse' => 'validpassword'];
// $_SERVER['REQUEST_METHOD'] = 'POST';
// unset($_SESSION['utilisateur']);
// $controleurTest->modele = new class {
//     public function verifierUtilisateurLDAP($username, $motDePasse) {
//         return $username === 'validuser' && $motDePasse === 'validpassword' ? ['username' => 'validuser'] : null;
//     }
// };
// ob_start();
// $controleurTest->verifierConnexion();
// $output = ob_get_clean();
// if ($_SESSION['utilisateur']['username'] === 'validuser') {
//     echo "PASS : L'utilisateur valide a été connecté avec succès.\n";
// } else {
//     echo "FAIL : L'utilisateur valide n'a pas été connecté.\n";
// }
