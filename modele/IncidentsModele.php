<?php
require_once __DIR__ . '/../config/_connexionBD.php';

class IncidentsModele {
    private $pdo;

    public function __construct() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Constructeur de la classe IncidentsModele
        
        Arguments: aucun
        Return: aucun
        */
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getDescription() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Récupérer la description en fonction de la connexion utilisateur

        Arguments: aucun
        Return: string
        */
        if (isset($_SESSION["utilisateur"]) && $_SESSION["utilisateur"]["role"] == "admin") {
            return "Vérifiez les derniers incidents et intervenez si besoin.";
        } else {
            return "Bienvenue sur la page des incidents. Vous pouvez voir les incidents.";
        }
    }

    public function recupererDerniersIncidents() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Récupérer les derniers incidents
        
        Arguments: aucun
        Return: array
        */
        $stmt = $this->pdo->query("SELECT ID_incident, nom, description, date_creation, niveauPriorite FROM incidents ORDER BY date_creation DESC");
        return $stmt->fetchAll();
    }

    public function recupererIncidentParId($id) {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Récupérer un incident en fonction de son ID
        
        Arguments: id (int)
        Return: array
        */
        $stmt = $this->pdo->prepare("SELECT * FROM incidents WHERE ID_incident = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function mettreAJourIncident($id, $nom, $description, $niveau) {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Mettre à jour un incident en fonction de son ID
        
        Arguments: id (int), nom (string), description (string), niveau (int)
        Return: aucun
        */
        $stmt = $this->pdo->prepare("UPDATE incidents SET nom = :nom, description = :description, niveauPriorite = :niveau WHERE ID_incident = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':niveau', $niveau, PDO::PARAM_INT);
        $stmt->execute();
    }
    

    public function ajouterIncident($nom, $description, $niveau) {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Ajouter un incident
        
        Arguments: nom (string), description (string), niveau (int)
        Return: aucun
        */
        $stmt = $this->pdo->prepare("INSERT INTO incidents (nom, description, niveauPriorite, date_creation) VALUES (:nom, :description, :niveau, NOW())");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':niveau', $niveau, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function supprimerIncident($id) {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Supprimer un incident en fonction de son ID
        
        Arguments: id (int)
        Return: string
        */
        $stmt = $this->pdo->prepare("DELETE FROM incidents WHERE ID_incident = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function recupererAlertesProduction() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Retourne toutes les alertes de surchage
        
        Arguments: aucun
        Return: array
        */
        $stmt = $this->pdo->query("
            SELECT *
            FROM alertes_surcharge
            ORDER BY date_signalement DESC
            LIMIT 10
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
}





// // Test 1 : Récupération de la description

// echo "Test 1 : Description de la page incidents\n";
// $incidentsModele = new IncidentsModele();
// $_SESSION['utilisateur'] = ['role' => 'admin'];
// $result = $incidentsModele->getDescription();
// if ($result === "Vérifiez les derniers incidents et intervenez si besoin.") {
//     echo "PASS : Description pour admin correcte.\n";
// } else {
//     echo "FAIL : Description pour admin incorrecte.\n";
// }

// $_SESSION['utilisateur'] = ['role' => 'user'];
// $result = $incidentsModele->getDescription();
// if ($result === "Bienvenue sur la page des incidents. Vous pouvez voir les incidents.") {
//     echo "PASS : Description pour utilisateur correcte.\n";
// } else {
//     echo "FAIL : Description pour utilisateur incorrecte.\n";
// }

// // Test 2 : Récupération des derniers incidents
// echo "\nTest 2 : Récupération des derniers incidents\n";
// $incidentsModele->pdo = new class {
//     public function query($query) {
//         return new class {
//             public function fetchAll() {
//                 return [
//                     ['ID_incident' => 1, 'nom' => 'Incident 1', 'description' => 'Description 1', 'date_creation' => '2024-12-18', 'niveauPriorite' => 2],
//                     ['ID_incident' => 2, 'nom' => 'Incident 2', 'description' => 'Description 2', 'date_creation' => '2024-12-17', 'niveauPriorite' => 3]
//                 ];
//             }
//         };
//     }
// };
// $result = $incidentsModele->recupererDerniersIncidents();
// if (count($result) === 2 && $result[0]['ID_incident'] === 1) {
//     echo "PASS : Derniers incidents correctement récupérés.\n";
// } else {
//     echo "FAIL : Derniers incidents non récupérés correctement.\n";
// }

// // Test 3 : Récupération d'un incident par ID
// echo "\nTest 3 : Récupération d'un incident par ID\n";
// $incidentsModele->pdo = new class {
//     public function prepare($query) {
//         return new class {
//             public function bindParam($param, $value, $type = null) {}
//             public function execute() {}
//             public function fetch() {
//                 return ['ID_incident' => 1, 'nom' => 'Incident 1', 'description' => 'Description 1', 'date_creation' => '2024-12-18', 'niveauPriorite' => 2];
//             }
//         };
//     }
// };
// $result = $incidentsModele->recupererIncidentParId(1);
// if ($result['ID_incident'] === 1) {
//     echo "PASS : Incident correctement récupéré par ID.\n";
// } else {
//     echo "FAIL : Incident non récupéré correctement par ID.\n";
// }

// // Test 4 : Mise à jour d'un incident
// echo "\nTest 4 : Mise à jour d'un incident\n";
// $incidentsModele->pdo = new class {
//     public function prepare($query) {
//         return new class {
//             public function bindParam($param, $value, $type = null) {}
//             public function execute() {
//                 echo "Mise à jour effectuée.\n";
//             }
//         };
//     }
// };
// $incidentsModele->mettreAJourIncident(1, 'Incident Modifié', 'Description modifiée', 3);

// // Test 5 : Ajout d'un incident
// echo "\nTest 5 : Ajout d'un incident\n";
// $incidentsModele->pdo = new class {
//     public function prepare($query) {
//         return new class {
//             public function bindParam($param, $value, $type = null) {}
//             public function execute() {
//                 echo "Incident ajouté.\n";
//             }
//         };
//     }
// };
// $incidentsModele->ajouterIncident('Nouvel Incident', 'Description du nouvel incident', 2);

// // Test 6 : Suppression d'un incident
// echo "\nTest 6 : Suppression d'un incident\n";
// $incidentsModele->pdo = new class {
//     public function prepare($query) {
//         return new class {
//             public function bindParam($param, $value, $type = null) {}
//             public function execute() {
//                 echo "Incident supprimé.\n";
//             }
//         };
//     }
// };
// $incidentsModele->supprimerIncident(1);