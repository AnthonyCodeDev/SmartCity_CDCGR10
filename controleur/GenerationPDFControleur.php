<?php

class GenerationPDFControleur {
    public function afficherPage() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Afficher la page
        
        Arguments: aucun
        Return: vue
        */
        require __DIR__ . '/../vue/generationPDF.php';
    }
}

// Exécuter le contrôleur
$controleur = new GenerationPDFControleur();
$controleur->afficherPage();
