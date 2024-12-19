<?php

class Erreur404Controleur {
    public function afficherPage() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Afficher la page
        
        Arguments: aucun
        Return: vue
        */
        require __DIR__ . '/../vue/404.php';
    }
}

// Exécuter le contrôleur
$controleur = new Erreur404Controleur();
$controleur->afficherPage();
