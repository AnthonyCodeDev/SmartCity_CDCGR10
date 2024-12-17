<?php

require_once __DIR__ . '/../modele/HomeModele.php';

class GenererRapportControleur {
    public function afficherPage() {
        // Récupérer les chiffres de l'accueil
        $homeModele = new HomeModele();
        $recupererInformationsGlobales = $homeModele->recupererInformationsGlobales();

        // Afficher la vue
        require __DIR__ . '/../vue/genererrapport.php';
    }
}

// Exécuter le contrôleur
$controleur = new GenererRapportControleur();
$controleur->afficherPage();
