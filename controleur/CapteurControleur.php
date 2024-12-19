<?php
session_start();

require_once __DIR__ . '/../config/config.php';

require_once __DIR__ . '/../modele/CapteurModele.php';

class CapteursControleur {
    private $modele;

    public function __construct() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Constructeur de la classe CapteursControleur
        
        Arguments: aucun
        Return: string
        */
        $this->modele = new CapteurModele();
    }

    public function checkPermission() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Vérifier les permissions
        
        Arguments: aucun
        Return: string
        */
        if (!(isset($_SESSION["utilisateur"]) && $_SESSION["utilisateur"]["role"] == "admin")) {
            header('Location: ' . BASE_URL);
            exit();
        }
    }

    public function afficherPage() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Afficher la page
        
        Arguments: aucun
        Return: vue
        */
        $this->checkPermission();
        $capteursSolaire = $this->modele->recupererCapteursParType(1); // 1 = solaire
        $capteursEolien = $this->modele->recupererCapteursParType(2); // 2 = éolien
    
        // Ajoute la production des dernières 6 heures à chaque capteur
        foreach ($capteursSolaire as &$capteur) {
            $capteur['production_6h'] = $this->modele->calculerProductionDernieresHeures($capteur['IPv4']);
        }
    
        foreach ($capteursEolien as &$capteur) {
            $capteur['production_6h'] = $this->modele->calculerProductionDernieresHeures($capteur['IPv4']);
        }
    
        require __DIR__ . '/../vue/capteurs.php';
    }
    
    

    public function changerEtatCapteur() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Changer l'état du capteur
        
        Arguments: aucun
        Return: string
        */
        $this->checkPermission();
        if (isset($_GET['id']) && isset($_GET['action'])) {
            $ipCapteur = htmlspecialchars($_GET['id']);
            $etat = ($_GET['action'] === 'start-sensor') ? true : false;

            $this->modele->mettreAJourEtatCapteur($ipCapteur, $etat);
        }
        header('Location: capteurs');
        exit();
    }
}

// Exécution du contrôleur
$controleur = new CapteursControleur();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'start-sensor':
        case 'stop-sensor':
            $controleur->changerEtatCapteur();
            break;
        default:
            $controleur->afficherPage();
    }
} else {
    $controleur->afficherPage();
}





// // Test 1 : Création de l'objet CapteursControleur
// echo "Test 1 : Constructeur\n";
// $controleurTest = new CapteursControleur();
// if ($controleurTest) {
//     echo "PASS : Le contrôleur a été créé avec succès.\n";
// } else {
//     echo "FAIL : Le contrôleur n'a pas pu être créé.\n";
// }

// // Test 2 : Vérification des permissions avec un utilisateur admin
// echo "\nTest 2 : Vérification des permissions avec utilisateur admin\n";
// $_SESSION['utilisateur'] = ['role' => 'admin'];
// try {
//     $controleurTest->checkPermission();
//     echo "PASS : Les permissions admin ont été validées correctement.\n";
// } catch (Exception $e) {
//     echo "FAIL : Les permissions admin n'ont pas été validées.\n";
// }

// // Test 3 : Vérification des permissions avec un utilisateur non-admin
// echo "\nTest 3 : Vérification des permissions avec utilisateur non-admin\n";
// $_SESSION['utilisateur'] = ['role' => 'user'];
// ob_start();
// $controleurTest->checkPermission();
// $output = ob_get_clean();
// if (headers_list()[0] === 'Location: ' . BASE_URL) {
//     echo "PASS : L'accès a été refusé pour un utilisateur non-admin.\n";
// } else {
//     echo "FAIL : L'accès n'a pas été refusé pour un utilisateur non-admin.\n";
// }

// // Test 4 : Affichage de la page des capteurs avec mock du modèle
// echo "\nTest 4 : Affichage de la page des capteurs\n";
// $controleurTest->modele = new class {
//     public function recupererCapteursParType($type) {
//         return [
//             ['IPv4' => '192.168.0.1'],
//             ['IPv4' => '192.168.0.2']
//         ];
//     }

//     public function calculerProductionDernieresHeures($ipv4) {
//         return rand(0, 100); // Valeurs simulées
//     }
// };
// $_SESSION['utilisateur'] = ['role' => 'admin'];
// ob_start();
// $controleurTest->afficherPage();
// $output = ob_get_clean();
// if (strpos($output, '192.168.0.1') !== false && strpos($output, '192.168.0.2') !== false) {
//     echo "PASS : La page des capteurs affiche correctement les données.\n";
// } else {
//     echo "FAIL : Les données des capteurs ne sont pas affichées correctement.\n";
// }

// // Test 5 : Changement de l'état d'un capteur avec mock du modèle
// echo "\nTest 5 : Changement de l'état d'un capteur\n";
// $_GET = ['id' => '192.168.0.1', 'action' => 'start-sensor'];
// $controleurTest->modele = new class {
//     public function mettreAJourEtatCapteur($ip, $etat) {
//         echo "Mise à jour du capteur $ip avec état " . ($etat ? 'ON' : 'OFF') . "\n";
//     }
// };
// ob_start();
// $controleurTest->changerEtatCapteur();
// $output = ob_get_clean();
// if (strpos($output, 'Mise à jour du capteur 192.168.0.1 avec état ON') !== false) {
//     echo "PASS : L'état du capteur a été mis à jour correctement.\n";
// } else {
//     echo "FAIL : L'état du capteur n'a pas été mis à jour correctement.\n";
// }
