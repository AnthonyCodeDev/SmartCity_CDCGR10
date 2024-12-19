<?php
require_once __DIR__ . '/../modele/HomeModele.php';

class GenerationPDFControleur {

    public function __construct() {
        $this->homeModele = new HomeModele();
    }

    private function formaterValeur($valeur) {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Formater la valeur
        
        Arguments: valeur (int)
        Return: int
        */
        // Si c'est un nombre entier, retourne-le sans modification
        if (is_numeric($valeur) && floor($valeur) == $valeur) {
            return (int) $valeur;
        }
        // Sinon, arrondit à 2 décimales
        return round($valeur, 2);
    }

    public function afficherPage() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Afficher la page
        
        Arguments: aucun
        Return: vue
        */

        $recupererInformationsGlobales = $this->homeModele->recupererInformationsGlobales();

        // for each in recupererInformationsGlobales, round 2 decimals
        $recupererInformationsGlobales = array_map(function($valeur) {
            return $this->formaterValeur($valeur);
        }, $recupererInformationsGlobales);

        require __DIR__ . '/../vue/generationPDF.php';
    }
}

// Exécuter le contrôleur
$controleur = new GenerationPDFControleur();
$controleur->afficherPage();
