<?php
require_once __DIR__ . '/../config/_connexionBD.php';

class AuthModele {

    private $ldapHost = 'ldap://example.local'; // Remplace par l'URL de ton serveur LDAP
    private $ldapPort = 389;                   // Port LDAP par défaut
    private $ldapBaseDn = 'dc=example,dc=local'; // Base DN de ton Active Directory

    // Méthode pour vérifier l'utilisateur dans LDAP
    public function verifierUtilisateurLDAP($email, $motDePasse) {
        // Connexion au serveur LDAP
        $ldapConnection = ldap_connect($this->ldapHost, $this->ldapPort);

        if (!$ldapConnection) {
            return false; // Échec de connexion au serveur LDAP
        }

        // Options LDAP
        ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);

        // Cherche l'utilisateur dans le répertoire LDAP
        $searchFilter = "(mail=$email)";
        $searchResult = ldap_search($ldapConnection, $this->ldapBaseDn, $searchFilter);

        if (!$searchResult) {
            return false; // Utilisateur non trouvé
        }

        $entries = ldap_get_entries($ldapConnection, $searchResult);

        if ($entries['count'] > 0) {
            // Récupération du DN (Distinguished Name) de l'utilisateur
            $userDn = $entries[0]['dn'];

            // Tentative d'authentification avec les identifiants fournis
            if (@ldap_bind($ldapConnection, $userDn, $motDePasse)) {
                // Retourne les informations de l'utilisateur
                return [
                    'nom' => $entries[0]['sn'][0] ?? '',
                    'prenom' => $entries[0]['givenname'][0] ?? '',
                    'email' => $entries[0]['mail'][0] ?? ''
                ];
            }
        }

        ldap_unbind($ldapConnection);
        return false; // Échec de connexion ou utilisateur non trouvé
    }
}
