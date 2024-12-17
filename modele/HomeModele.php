<?php
require_once __DIR__ . '/../config/_connexionBD.php';

class HomeModele {

    private $pdo;

    public function __construct() {
        // QUI: Anthony Vergeylen
        // QUAND: 17/12/2024
        // QUOI: Initialisation de la connexion PDO

        global $pdo; // Utilise la connexion PDO globale
        $this->pdo = $pdo;
    }

    public function getWelcomeMessage() {
        // QUI: Anthony Vergeylen
        // QUAND: 17/12/2024
        // QUOI: Envoyer si tout va bien, sinon tout va mal

        $utilisateur = $_SESSION['utilisateur'] ?? null;

        if (!$utilisateur) {
            return "Tout va mal ! 😭";
        }

        return "Tout va bien $utilisateur[nom] $utilisateur[prenom] ! 😊";
    }

    public function recupererInformationsGlobales() {
        // QUI: Anthony Vergeylen
        // QUAND: 17/12/2024
        // QUOI: Récupérer la consommation, la production solaire et la production éolienne

        $consommation = 1.2;
        $productionSolaire = 2.3;
        $productionEolienne = 3.4;

        return [
            'consommation' => $consommation,
            'productionSolaire' => $productionSolaire,
            'productionEolienne' => $productionEolienne,
        ];
    }
}
