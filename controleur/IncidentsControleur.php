<?php

// display errors for development
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../modele/IncidentsModele.php';

class IncidentsControleur {
    private $modele;

    public function __construct() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Constructeur de la classe IncidentsControleur
        
        Arguments: aucun
        Return: aucun
        */
        $this->modele = new IncidentsModele();
    }

    public function afficherPage() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Afficher la page des incidents
        
        Arguments: aucun
        Return: aucun
        */

        $recupererAlertesProduction = $this->modele->recupererAlertesProduction();
        $description = $this->modele->getDescription();
        $recupererDerniersIncidents = $this->modele->recupererDerniersIncidents();
        $incidentEdit = null;
    
        if (isset($_GET['action']) && $_GET['action'] === 'edit-incident' && isset($_GET['id'])) {
            $incidentEdit = $this->modele->recupererIncidentParId((int)$_GET['id']);
        }
    
        require __DIR__ . '/../vue/incidents.php';
    }

    public function checkPermission() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Vérifier les permissions de l'utilisateur
        
        Arguments: aucun
        Return: aucun
        */
        if (!(isset($_SESSION["utilisateur"]) && $_SESSION["utilisateur"]["role"] == "admin")) {
            header('Location: incidents');
            exit();
        }
    }

    public function creerIncident() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Créer un nouvel incident
        
        Arguments: aucun
        Return: aucun
        */
        $this->checkPermission();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom']);
            $description = trim($_POST['description']);
            $niveau = (int) $_POST['niveau'];
    
            // Vérification des validations
            if (!$this->validerIncident($nom, $description, $niveau)) {
                $_SESSION['error'] = "Données invalides. Assurez-vous que les champs respectent les règles.";
                header('Location: incidents?action=create-incident');
                exit();
            }
    
            // Ajouter l'incident si les données sont valides
            $this->modele->ajouterIncident($nom, $description, $niveau);
            header('Location: incidents');
            exit();
        }
    }
    
    public function mettreAJourIncident() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Mettre à jour un incident existant
        
        Arguments: aucun
        Return: aucun
        */
        $this->checkPermission();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $nom = trim($_POST['nom']);
            $description = trim($_POST['description']);
            $niveau = (int)$_POST['niveau'];
    
            // Vérification des validations
            if (!$this->validerIncident($nom, $description, $niveau)) {
                $_SESSION['error'] = "Données invalides. Assurez-vous que les champs respectent les règles.";
                header("Location: incidents?action=edit-incident&id=$id");
                exit();
            }
    
            // Mettre à jour l'incident si les données sont valides
            $this->modele->mettreAJourIncident($id, $nom, $description, $niveau);
            header('Location: incidents');
            exit();
        }
    }
    
    private function validerIncident($nom, $description, $niveau) {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Valider les données d'un incident
        
        Arguments: nom (string), description (string), niveau (int)
        Return: bool
        */
        $this->checkPermission();
        // Valide le nom (min 3, max 100)
        if (strlen($nom) < 3 || strlen($nom) > 100) {
            return false;
        }
    
        // Valide la description (min 12, max 100)
        if (strlen($description) < 12 || strlen($description) > 100) {
            return false;
        }
    
        // Valide le niveau (1, 2 ou 3)
        if (!in_array($niveau, [1, 2, 3])) {
            return false;
        }
    
        return true;
    }
    

    public function supprimerIncident() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Supprimer un incident
        
        Arguments: aucun
        Return: aucun
        */
        $this->checkPermission();
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = (int) $_GET['id'];
            $this->modele->supprimerIncident($id);
        }
        header('Location: incidents');
        exit();
    }

}

// Exécuter le contrôleur
$controleur = new IncidentsControleur();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'store-incident': // Créer un nouvel incident
            $controleur->creerIncident();
            break;
        case 'delete-incident': // Supprimer un incident
            $controleur->supprimerIncident();
            break;
        case 'edit-incident': // Préparer la modification
            $controleur->afficherPage();
            break;
        case 'update-incident': // Mettre à jour un incident existant
            $controleur->mettreAJourIncident();
            break;
        default:
            $controleur->afficherPage();
            break;
    }
} else {
    $controleur->afficherPage();
}



// // Test 1 : Création de l'objet IncidentsControleur
// echo "Test 1 : Constructeur\n";
// $controleurTest = new IncidentsControleur();
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
// if (headers_list()[0] === 'Location: incidents') {
//     echo "PASS : L'accès a été refusé pour un utilisateur non-admin.\n";
// } else {
//     echo "FAIL : L'accès n'a pas été refusé pour un utilisateur non-admin.\n";
// }

// // Test 4 : Création d'un incident avec données valides
// echo "\nTest 4 : Création d'un incident avec données valides\n";
// $_SERVER['REQUEST_METHOD'] = 'POST';
// $_POST = ['nom' => 'Incident Test', 'description' => 'Description valide', 'niveau' => 2];
// $_SESSION['utilisateur'] = ['role' => 'admin'];
// $controleurTest->modele = new class {
//     public function ajouterIncident($nom, $description, $niveau) {
//         echo "Incident ajouté : $nom, $description, Niveau: $niveau\n";
//     }
// };
// ob_start();
// $controleurTest->creerIncident();
// $output = ob_get_clean();
// if (strpos($output, 'Incident ajouté') !== false) {
//     echo "PASS : L'incident a été créé correctement.\n";
// } else {
//     echo "FAIL : L'incident n'a pas été créé correctement.\n";
// }

// // Test 5 : Mise à jour d'un incident avec données valides
// echo "\nTest 5 : Mise à jour d'un incident avec données valides\n";
// $_SERVER['REQUEST_METHOD'] = 'POST';
// $_POST = ['id' => 1, 'nom' => 'Incident Modifié', 'description' => 'Nouvelle description', 'niveau' => 3];
// $controleurTest->modele = new class {
//     public function mettreAJourIncident($id, $nom, $description, $niveau) {
//         echo "Incident mis à jour : ID $id, $nom, $description, Niveau: $niveau\n";
//     }
// };
// ob_start();
// $controleurTest->mettreAJourIncident();
// $output = ob_get_clean();
// if (strpos($output, 'Incident mis à jour') !== false) {
//     echo "PASS : L'incident a été mis à jour correctement.\n";
// } else {
//     echo "FAIL : L'incident n'a pas été mis à jour correctement.\n";
// }

// // Test 6 : Suppression d'un incident
// echo "\nTest 6 : Suppression d'un incident\n";
// $_GET = ['id' => 1];
// $controleurTest->modele = new class {
//     public function supprimerIncident($id) {
//         echo "Incident supprimé : ID $id\n";
//     }
// };
// ob_start();
// $controleurTest->supprimerIncident();
// $output = ob_get_clean();
// if (strpos($output, 'Incident supprimé') !== false) {
//     echo "PASS : L'incident a été supprimé correctement.\n";
// } else {
//     echo "FAIL : L'incident n'a pas été supprimé correctement.\n";
// }

// // Test 7 : Validation des données d'un incident
// echo "\nTest 7 : Validation des données d'un incident\n";
// $valid = $controleurTest->validerIncident('Nom Valide', 'Description valide', 2);
// if ($valid) {
//     echo "PASS : Les données de l'incident ont été validées correctement.\n";
// } else {
//     echo "FAIL : Les données de l'incident n'ont pas été validées correctement.\n";
// }