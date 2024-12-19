<?php
require_once __DIR__ . '/../config/_connexionBD.php';

class HomeModele {

    private $pdo;

    public function __construct() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Constructeur de la classe HomeModele

        Arguments: aucun
        Return: string
        */
        global $pdo; // Utilise la connexion PDO globale
        $this->pdo = $pdo;
    }

    public function getWelcomeMessage() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Retourne un message de bienvenue
        
        Arguments: aucun
        Return: string
        */

        $utilisateur = $_SESSION['utilisateur'] ?? null;

        if (!$utilisateur) {
            return "Tout va mal ! 😭";
        }

        $nomUtilisateur = ucwords("$utilisateur[nom] $utilisateur[prenom]");

        return "Tout va bien $nomUtilisateur ! 😊";
    }

    public function recupererProductionDernieres24h($typeEnergie) {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Retourne la production d'énergie des 24 dernières heures
        
        Arguments: typeEnergie (int)
        Return: int
        */
        $stmt = $this->pdo->prepare("
            SELECT SUM(valeur) as total
            FROM capteurs_energie
            WHERE type_energie = :typeEnergie
            AND date_mesure >= NOW() - INTERVAL 24 HOUR
        ");
        $stmt->bindParam(':typeEnergie', $typeEnergie, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0; // Retourne 0 si aucune donnée
    }
    
    public function recupererInformationsGlobales() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Retourne les informations globales de consommation et production
        
        Arguments: aucun
        Return: array
        */
        $consommation = $this->recupererConsommationDernieres24h(); // Consommation sur 24h
        $productionSolaire = $this->recupererProductionDernieres24h(1); // Type 1 pour solaire
        $productionEolienne = $this->recupererProductionDernieres24h(2); // Type 2 pour éolien
    
        return [
            'consommation' => $consommation,
            'productionSolaire' => $productionSolaire,
            'productionEolienne' => $productionEolienne,
        ];
    }

    public function recupererConsommationDernieres24h() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Retourne la consommation d'énergie des 24 dernières heures
        
        Arguments: aucun
        Return: int
        */
        $stmt = $this->pdo->query("
            SELECT SUM(consommation) as total
            FROM consommation_energie
            WHERE date >= NOW() - INTERVAL 30 DAY
        ");
        $result = $stmt->fetch();
        return $result['total'] ?? 0; // Retourne 0 si aucune donnée
    }

    public function recupererConsommation30Jours() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Retourne la consommation d'énergie des 30 derniers jours
        
        Arguments: aucun
        Return: array
        */
        $stmt = $this->pdo->query("
            SELECT DATE(date) as jour, SUM(consommation) as total
            FROM consommation_energie
            WHERE date >= NOW() - INTERVAL 30 DAY
            GROUP BY DATE(date)
            ORDER BY DATE(date) ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function recupererProduction30Jours() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Retourne la production d'énergie des 30 derniers jours
        
        Arguments: aucun
        Return: array
        */
        $stmt = $this->pdo->query("
            SELECT DATE(date_mesure) as jour, 
                   SUM(CASE WHEN type_energie = 1 THEN valeur ELSE 0 END) as solaire,
                   SUM(CASE WHEN type_energie = 2 THEN valeur ELSE 0 END) as eolienne
            FROM capteurs_energie
            WHERE date_mesure >= NOW() - INTERVAL 30 DAY
            GROUP BY DATE(date_mesure)
            ORDER BY DATE(date_mesure) ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
