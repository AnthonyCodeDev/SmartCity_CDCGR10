<?php

class DeconnexionControleur {
    public function afficherPage() {
        require __DIR__ . '/../vue/deconnexion.php';
    }
}

// Exécuter le contrôleur
$controleur = new DeconnexionControleur();
$controleur->afficherPage();
