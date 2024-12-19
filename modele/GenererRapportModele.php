<?php
require_once __DIR__ . '/../config/_connexionBD.php';

class GenererRapportModele {

    private $pdo;

    public function __construct() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Constructeur de la classe GenererRapportModele

        Arguments: aucun
        Return: string
        */

        global $pdo; // Utilise la connexion PDO globale
        $this->pdo = $pdo;
    }

    public function recupererRapports() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Récupérer tous les rapports de la table energetic_report
        
        Arguments: aucun
        Return: array
        */
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM energetic_report
            ORDER BY date_rapport DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
