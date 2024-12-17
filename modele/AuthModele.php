<?php
require_once __DIR__ . '/../config/_connexionBD.php';

class AuthModele {

    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // Récupère un utilisateur factice simulant un LDAP
    public function recupererUtilisateurLDAP($email) {
        // Simulation d'utilisateur stocké en dur (à remplacer par une vraie requête LDAP)
        $utilisateurs = [
            'a@b.c' => [
                'nom' => 'Doe',
                'prenom' => 'John',
                'email' => 'john@doe.fr',
                'mot_de_passe' => 'motdepasse   ' // À remplacer par un mot de passe haché
            ]
        ];

        return $utilisateurs[$email] ?? null;
    }
}
