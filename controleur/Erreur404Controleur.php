<?php

class Erreur404Controleur {
    public function afficherPage() {
        require __DIR__ . '/../vue/404.php';
    }
}

// Exécuter le contrôleur
$controleur = new Erreur404Controleur();
$controleur->afficherPage();
