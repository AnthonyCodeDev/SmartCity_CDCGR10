<?php
session_start();

require_once __DIR__ . '/../modele/CapteurModele.php';

class CapteursControleur {
    private $modele;

    public function __construct() {
        $this->modele = new CapteurModele();
    }

    public function afficherPage() {
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
