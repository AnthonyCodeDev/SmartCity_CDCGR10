<?php
require_once __DIR__ . '/../config/_connexionBD.php';

class AuthModele {

    private $ldapHost = 'ldap://192.168.100.2'; // URL de ton serveur LDAP
    private $ldapPort = 389;                   // Port LDAP par défaut
    private $ldapBaseDn = 'DC=smartcity,DC=lan';

    // Méthode pour vérifier l'utilisateur dans LDAP
    public function verifierUtilisateurLDAP($username, $motDePasse) {
        // Connexion au serveur LDAP
        $ldapConnection = ldap_connect($this->ldapHost, $this->ldapPort);

        if (!$ldapConnection) {
            echo "Impossible de se connecter au serveur LDAP";
            return false; // Échec de connexion au serveur LDAP
        }

        ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);
        // afficher toutes les infos de l'ad
        $ldapBind = @ldap_bind($ldapConnection, $username . "@smartcity.lan", $motDePasse);

        if (!$ldapBind) {
            echo "LDAP bind failed: " . ldap_error($ldapConnection);
        }

        // search the user
        // $result = ldap_search($ldapConnection, "DC=smartcity,DC=lan", "(objectclass=*)");
        // $data = ldap_get_entries($ldapConnection, $result);

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

        // Options LDAP

        // Préparation du filtre de recherche
        $searchFilter = "(sAMAccountName=$username)";
        echo "Base DN utilisé : " . $this->ldapBaseDn . "<br>";
        echo "Filtre de recherche : " . $searchFilter . "<br>";

        // Recherche dans LDAP
        $searchResult = @ldap_search($ldapConnection, $this->ldapBaseDn, $searchFilter);

        if (!$searchResult) {
            echo "Erreur LDAP lors de la recherche : " . ldap_error($ldapConnection) . "<br>";
            ldap_close($ldapConnection);
            return false;
        }

        $entries = ldap_get_entries($ldapConnection, $searchResult);

        if ($entries['count'] === 0) {
            echo "Utilisateur non trouvé dans le répertoire LDAP.<br>";
            ldap_close($ldapConnection);
            return false;
        }

        // Récupération du DN (Distinguished Name) de l'utilisateur
        $userDn = $entries[0]['dn'];
        echo "DN de l'utilisateur trouvé : $userDn<br>";

        // Tentative d'authentification avec les identifiants fournis
        if (@ldap_bind($ldapConnection, $userDn, $motDePasse)) {
            echo "Authentification réussie pour l'utilisateur $username.<br>";

            ldap_unbind($ldapConnection);

            // Retourne les informations utiles sur l'utilisateur
            return [
                'nom' => $entries[0]['sn'][0] ?? '',
                'prenom' => $entries[0]['givenname'][0] ?? '',
                'email' => $entries[0]['mail'][0] ?? ''
            ];
        } else {
            echo "Échec de l'authentification : mot de passe incorrect.<br>";
        }

        ldap_unbind($ldapConnection);
        return false; // Échec de connexion ou utilisateur non trouvé
    }
}
