<?php

class IncidentsControleur {
    public function afficherPage() {
        require __DIR__ . '/../vue/incidents.php';
    }
}

// Exécuter le contrôleur
$controleur = new IncidentsControleur();
$controleur->afficherPage();
