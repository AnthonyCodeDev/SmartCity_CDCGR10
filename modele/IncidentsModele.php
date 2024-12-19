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
    
    
    
}
