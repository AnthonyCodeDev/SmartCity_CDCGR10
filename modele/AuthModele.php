<?php
require_once __DIR__ . '/../config/_connexionBD.php';

class AuthModele {

    private $ldapHost = 'ldap://192.168.100.2'; // URL de ton serveur LDAP
    private $ldapPort = 389;                   // Port LDAP par défaut
    private $ldapBaseDn = 'DC=smartcity,DC=lan';

    // Méthode pour vérifier l'utilisateur dans LDAP
    public function verifierUtilisateurLDAP($username, $motDePasse) {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Vérifier l'utilisateur dans LDAP
        
        Arguments: username (string), motDePasse (string)
        Return: array
        */

        // Connexion au serveur LDAP
        $ldapConnection = ldap_connect($this->ldapHost, $this->ldapPort);

        if (!$ldapConnection) {
            return false; // Échec de connexion au serveur LDAP
        }

        ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);

        $ldapBind = @ldap_bind($ldapConnection, $username . "@smartcity.lan", $motDePasse);

        if (!$ldapBind) {
            $_SESSION['error'] = "Échec de l'authentification : mot de passe incorrect.";
            ldap_close($ldapConnection);
            return false;
        }

        $searchFilter = "(sAMAccountName=$username)";
        $searchResult = @ldap_search($ldapConnection, $this->ldapBaseDn, $searchFilter);

        if (!$searchResult) {
            $_SESSION['error'] = "Erreur LDAP lors de la recherche : " . ldap_error($ldapConnection);
            ldap_close($ldapConnection);
            return false;
        }

        $entries = ldap_get_entries($ldapConnection, $searchResult);

        if ($entries['count'] === 0) {
            $_SESSION['error'] = "Utilisateur non trouvé dans le répertoire LDAP.";
            ldap_close($ldapConnection);
            return false;
        }

        $userDn = $entries[0]['dn'];

        if (@ldap_bind($ldapConnection, $userDn, $motDePasse)) {
            $memberOf = $entries[0]['memberof'] ?? [];
            $role = 'user'; // Rôle par défaut

            if (is_array($memberOf) && in_array('CN=GG_AdminEnergie,OU=GG,OU=Groups,DC=smartcity,DC=lan', $memberOf)) {
                $role = 'admin';
            }

            ldap_unbind($ldapConnection);

            return [
                'nom' => $entries[0]['sn'][0] ?? '',
                'prenom' => $entries[0]['givenname'][0] ?? '',
                'email' => $entries[0]['mail'][0] ?? '',
                'role' => $role
            ];
        } else {
            $_SESSION['error'] = "Échec de l'authentification : mot de passe incorrect.";
        }

        ldap_unbind($ldapConnection);
        return false;
    }
}
