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
        $this->modele = new IncidentsModele();
    }

    public function afficherPage() {
        $recupererDerniersIncidents = $this->modele->recupererDerniersIncidents();
        $incidentEdit = null;
    
        if (isset($_GET['action']) && $_GET['action'] === 'edit-incident' && isset($_GET['id'])) {
            $incidentEdit = $this->modele->recupererIncidentParId((int)$_GET['id']);
        }
    
        require __DIR__ . '/../vue/incidents.php';
    }
    public function creerIncident() {
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
