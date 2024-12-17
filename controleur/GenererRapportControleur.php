<?php

class GenererRapportControleur {
    public function afficherPage() {
        require __DIR__ . '/../vue/genererrapport.php';
    }
}

// Exécuter le contrôleur
$controleur = new GenererRapportControleur();
$controleur->afficherPage();
