<?php
require_once __DIR__ . '/../config/_connexionBD.php';

class CapteurModele {
    private $pdo;

    public function __construct() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Constructeur de la classe CapteurModele

        Arguments: aucun
        Return: string
        */
        global $pdo;
        $this->pdo = $pdo;
    }

    public function calculerProductionDernieresHeures($ipCapteur, $heures = 6) {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Calculer la production des dernières heures
        
        Arguments: ipCapteur (string), heures (int)
        Return: int
        */
        $stmt = $this->pdo->prepare("
            SELECT SUM(valeur) as total
            FROM capteurs_energie
            WHERE id_capteur = :ipCapteur
            AND date_mesure >= NOW() - INTERVAL :heures HOUR
        ");
        $stmt->bindParam(':ipCapteur', $ipCapteur);
        $stmt->bindParam(':heures', $heures, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0; // Retourne 0 si aucun résultat
    }
        

    public function recupererCapteursParType($type) {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Récupérer les capteurs par type
        
        Arguments: type (int)
        Return: array
        */
        $stmt = $this->pdo->prepare("
            SELECT 
                s.IPv4, 
                s.Name, 
                s.StateUp, 
                s.DateAdded,
                COUNT(c.id) as nombre_donnees
            FROM sensor s
            LEFT JOIN capteurs_energie c ON s.IPv4 = c.id_capteur
            WHERE s.ID_SensorType = :type
            GROUP BY s.IPv4, s.Name, s.StateUp, s.DateAdded
            ORDER BY s.StateUp DESC, nombre_donnees DESC, s.DateAdded DESC
        ");
        $stmt->bindParam(':type', $type, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère tous les capteurs avec leur statut
    public function recupererCapteurs() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Récupérer tous les capteurs avec leur statut
        
        Arguments: aucun
        Return: array
        */
        $stmt = $this->pdo->query("
            SELECT IPv4, Name, StateUp, DateAdded
            FROM sensor
            ORDER BY DateAdded DESC
        ");
        return $stmt->fetchAll();
    }

    // Compte le nombre de données de consommation pour chaque capteur
    public function compterDonneesCapteur($ipCapteur) {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Compter le nombre de données de consommation pour chaque capteur
        
        Arguments: ipCapteur (string)
        Return: int
        */
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total
            FROM capteurs_energie
            WHERE id_capteur = :ipCapteur
        ");
        $stmt->bindParam(':ipCapteur', $ipCapteur);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    // Met à jour l'état d'un capteur (Start/Stop)
    public function mettreAJourEtatCapteur($ipCapteur, $etat) {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Mettre à jour l'état d'un capteur (Start/Stop)
        
        Arguments: ipCapteur (string), etat (bool)
        Return: aucun
        */
        $stmt = $this->pdo->prepare("
            UPDATE Sensor
            SET StateUp = :etat
            WHERE IPv4 = :ipCapteur
        ");
        $stmt->bindParam(':etat', $etat, PDO::PARAM_BOOL);
        $stmt->bindParam(':ipCapteur', $ipCapteur);
        $stmt->execute();
    }
}
