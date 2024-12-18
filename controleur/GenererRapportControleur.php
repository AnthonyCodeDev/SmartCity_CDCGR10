<?php

require_once __DIR__ . '/../modele/HomeModele.php';

class GenererRapportControleur {
    private $modele;

    public function __construct() {
        $this->modele = new HomeModele();
    }

    private function formaterValeur($valeur) {
        // Si c'est un nombre entier, retourne-le sans modification
        if (is_numeric($valeur) && floor($valeur) == $valeur) {
            return (int) $valeur;
        }
        // Sinon, arrondit à 2 décimales
        return round($valeur, 2);
    }   

    public function afficherPage() {
        // Récupérer les chiffres de l'accueil
        $recupererInformationsGlobales = $this->modele->recupererInformationsGlobales();

        // for each in recupererInformationsGlobales, round 2 decimals
        $recupererInformationsGlobales = array_map(function($valeur) {
            return $this->formaterValeur($valeur);
        }, $recupererInformationsGlobales);

        // Afficher la vue
        require __DIR__ . '/../vue/genererrapport.php';
    }
}

// Exécuter le contrôleur
$controleur = new GenererRapportControleur();
$controleur->afficherPage();
