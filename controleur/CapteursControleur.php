<?php

class CapteursControleur {
    public function afficherPage() {
        require __DIR__ . '/../vue/capteurs.php';
    }
}

// Exécuter le contrôleur
$controleur = new CapteursControleur();
$controleur->afficherPage();
