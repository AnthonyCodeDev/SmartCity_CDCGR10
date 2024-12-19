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




// // Test 1 : Récupération du message de bienvenue
// echo "Test 1 : Message de bienvenue\n";
// $homeModele = new HomeModele();
// $_SESSION['utilisateur'] = ['nom' => 'Doe', 'prenom' => 'John'];
// $result = $homeModele->getWelcomeMessage();
// if ($result === "Tout va bien Doe John ! 😊") {
//     echo "PASS : Message de bienvenue correctement généré.\n";
// } else {
//     echo "FAIL : Message de bienvenue incorrect.\n";
// }

// // Test 2 : Calcul de la production des dernières 24 heures
// echo "\nTest 2 : Production des dernières 24 heures\n";
// $homeModele->pdo = new class {
//     public function prepare($query) {
//         return new class {
//             public function bindParam($param, $value, $type = null) {}
//             public function execute() {}
//             public function fetch() {
//                 return ['total' => 200]; // Valeur simulée
//             }
//         };
//     }
// };
// $result = $homeModele->recupererProductionDernieres24h(1);
// if ($result === 200) {
//     echo "PASS : Production des dernières 24 heures correctement calculée.\n";
// } else {
//     echo "FAIL : Production des dernières 24 heures incorrecte.\n";
// }

// // Test 3 : Récupération des informations globales
// echo "\nTest 3 : Informations globales\n";
// $homeModele->pdo = new class {
//     public function prepare($query) {
//         return new class {
//             public function bindParam($param, $value, $type = null) {}
//             public function execute() {}
//             public function fetch() {
//                 return ['total' => 100];
//             }
//         };
//     }
//     public function query($query) {
//         return new class {
//             public function fetch() {
//                 return ['total' => 300];
//             }
//         };
//     }
// };
// $result = $homeModele->recupererInformationsGlobales();
// if ($result['consommation'] === 300 && $result['productionSolaire'] === 100 && $result['productionEolienne'] === 100) {
//     echo "PASS : Informations globales correctement récupérées.\n";
// } else {
//     echo "FAIL : Informations globales incorrectes.\n";
// }

// // Test 4 : Récupération de la consommation des 30 derniers jours
// echo "\nTest 4 : Consommation des 30 derniers jours\n";
// $homeModele->pdo = new class {
//     public function query($query) {
//         return new class {
//             public function fetchAll($fetchStyle = null) {
//                 return [
//                     ['jour' => '2024-12-01', 'total' => 50],
//                     ['jour' => '2024-12-02', 'total' => 70]
//                 ];
//             }
//         };
//     }
// };
// $result = $homeModele->recupererConsommation30Jours();
// if (count($result) === 2 && $result[0]['jour'] === '2024-12-01' && $result[0]['total'] === 50) {
//     echo "PASS : Consommation des 30 derniers jours correctement récupérée.\n";
// } else {
//     echo "FAIL : Consommation des 30 derniers jours incorrecte.\n";
// }

// // Test 5 : Récupération de la production des 30 derniers jours
// echo "\nTest 5 : Production des 30 derniers jours\n";
// $homeModele->pdo = new class {
//     public function query($query) {
//         return new class {
//             public function fetchAll($fetchStyle = null) {
//                 return [
//                     ['jour' => '2024-12-01', 'solaire' => 30, 'eolienne' => 40],
//                     ['jour' => '2024-12-02', 'solaire' => 50, 'eolienne' => 60]
//                 ];
//             }
//         };
//     }
// };
// $result = $homeModele->recupererProduction30Jours();
// if (count($result) === 2 && $result[0]['jour'] === '2024-12-01' && $result[0]['solaire'] === 30 && $result[0]['eolienne'] === 40) {
//     echo "PASS : Production des 30 derniers jours correctement récupérée.\n";
// } else {
//     echo "FAIL : Production des 30 derniers jours incorrecte.\n";
// }