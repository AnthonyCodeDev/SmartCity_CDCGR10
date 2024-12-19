<?php
session_start();

require_once __DIR__ . '/../config/config.php';

require_once __DIR__ . '/../modele/CapteurModele.php';

class CapteursControleur {
    private $modele;

    public function __construct() {
        $this->modele = new CapteurModele();
    }

    public function checkPermission() {
        if (!(isset($_SESSION["utilisateur"]) && $_SESSION["utilisateur"]["role"] == "admin")) {
            header('Location: ' . BASE_URL);
            exit();
        }
    }

    public function afficherPage() {
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
    $this->checkPermission();
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
