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






// // Test 1 : Calculer la production des dernières heures avec des données valides
// echo "Test 1 : Calcul de la production des dernières heures\n";
// $capteurModele = new CapteurModele();
// $capteurModele->pdo = new class {
//     public function prepare($query) {
//         return new class {
//             public function bindParam($param, $value, $type = null) {}
//             public function execute() {}
//             public function fetch() {
//                 return ['total' => 150]; // Valeur simulée
//             }
//         };
//     }
// };
// $result = $capteurModele->calculerProductionDernieresHeures('192.168.0.1', 6);
// if ($result === 150) {
//     echo "PASS : Production correctement calculée.\n";
// } else {
//     echo "FAIL : La production n'a pas été correctement calculée.\n";
// }

// // Test 2 : Récupérer les capteurs par type avec des données valides
// echo "\nTest 2 : Récupération des capteurs par type\n";
// $capteurModele->pdo = new class {
//     public function prepare($query) {
//         return new class {
//             public function bindParam($param, $value, $type = null) {}
//             public function execute() {}
//             public function fetchAll($fetchStyle = null) {
//                 return [
//                     ['IPv4' => '192.168.0.1', 'Name' => 'Capteur 1', 'StateUp' => 1, 'DateAdded' => '2024-12-18', 'nombre_donnees' => 5],
//                     ['IPv4' => '192.168.0.2', 'Name' => 'Capteur 2', 'StateUp' => 0, 'DateAdded' => '2024-12-17', 'nombre_donnees' => 3]
//                 ];
//             }
//         };
//     }
// };
// $result = $capteurModele->recupererCapteursParType(1);
// if (count($result) === 2 && $result[0]['IPv4'] === '192.168.0.1') {
//     echo "PASS : Capteurs correctement récupérés.\n";
// } else {
//     echo "FAIL : Les capteurs n'ont pas été correctement récupérés.\n";
// }

// // Test 3 : Mettre à jour l'état d'un capteur
// echo "\nTest 3 : Mise à jour de l'état d'un capteur\n";
// $capteurModele->pdo = new class {
//     public function prepare($query) {
//         return new class {
//             public function bindParam($param, $value, $type = null) {}
//             public function execute() {
//                 echo "Mise à jour de l'état du capteur effectuée.\n";
//             }
//         };
//     }
// };
// $capteurModele->mettreAJourEtatCapteur('192.168.0.1', true);

// // Test 4 : Compter les données d'un capteur
// echo "\nTest 4 : Compter les données d'un capteur\n";
// $capteurModele->pdo = new class {
//     public function prepare($query) {
//         return new class {
//             public function bindParam($param, $value, $type = null) {}
//             public function execute() {}
//             public function fetch() {
//                 return ['total' => 10]; // Valeur simulée
//             }
//         };
//     }
// };
// $result = $capteurModele->compterDonneesCapteur('192.168.0.1');
// if ($result === 10) {
//     echo "PASS : Nombre de données correctement compté.\n";
// } else {
//     echo "FAIL : Le nombre de données n'a pas été correctement compté.\n";
// }

// // Test 5 : Récupérer tous les capteurs
// echo "\nTest 5 : Récupération de tous les capteurs\n";
// $capteurModele->pdo = new class {
//     public function query($query) {
//         return new class {
//             public function fetchAll() {
//                 return [
//                     ['IPv4' => '192.168.0.1', 'Name' => 'Capteur 1', 'StateUp' => 1, 'DateAdded' => '2024-12-18'],
//                     ['IPv4' => '192.168.0.2', 'Name' => 'Capteur 2', 'StateUp' => 0, 'DateAdded' => '2024-12-17']
//                 ];
//             }
//         };
//     }
// };
// $result = $capteurModele->recupererCapteurs();
// if (count($result) === 2 && $result[0]['IPv4'] === '192.168.0.1') {
//     echo "PASS : Tous les capteurs ont été correctement récupérés.\n";
// } else {
//     echo "FAIL : Les capteurs n'ont pas été correctement récupérés.\n";
// }