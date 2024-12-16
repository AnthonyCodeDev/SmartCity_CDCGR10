<?php

require_once __DIR__ . '/../modele/HomeModele.php';

class HomeControleur {
    private $modele;

    public function __construct() {
        $this->modele = new HomeModele();
    }

    public function afficherPage() {
        $message = $this->modele->getMessage();
        require __DIR__ . '/../vue/home.php';
    }
}

// Exécuter le contrôleur
$controleur = new HomeControleur();
$controleur->afficherPage();
