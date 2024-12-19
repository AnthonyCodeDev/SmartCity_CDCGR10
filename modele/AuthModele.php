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






// // Test 1 : Vérification de la connexion LDAP avec des identifiants valides
// echo "Test 1 : Connexion LDAP valide\n";
// $authModele = new AuthModele();

// // Mock de connexion LDAP valide
// $authModele->ldapHost = "mock://ldap-valid";
// $authModele->ldapPort = 389;
// $_SESSION = [];
// $result = $authModele->verifierUtilisateurLDAP("validUser", "validPassword");
// if ($result && $result['role'] === 'user') {
//     echo "PASS : Connexion réussie avec utilisateur valide.\n";
// } else {
//     echo "FAIL : Connexion échouée avec utilisateur valide.\n";
// }

// // Test 2 : Connexion LDAP avec mot de passe incorrect
// echo "\nTest 2 : Connexion LDAP avec mot de passe incorrect\n";
// $_SESSION = [];
// $result = $authModele->verifierUtilisateurLDAP("validUser", "wrongPassword");
// if (!$result && isset($_SESSION['error']) && strpos($_SESSION['error'], 'mot de passe incorrect') !== false) {
//     echo "PASS : Échec attendu avec mot de passe incorrect.\n";
// } else {
//     echo "FAIL : Résultat inattendu avec mot de passe incorrect.\n";
// }

// // Test 3 : Connexion LDAP avec utilisateur inexistant
// echo "\nTest 3 : Connexion LDAP avec utilisateur inexistant\n";
// $_SESSION = [];
// $result = $authModele->verifierUtilisateurLDAP("nonexistentUser", "somePassword");
// if (!$result && isset($_SESSION['error']) && strpos($_SESSION['error'], 'Utilisateur non trouvé') !== false) {
//     echo "PASS : Échec attendu avec utilisateur inexistant.\n";
// } else {
//     echo "FAIL : Résultat inattendu avec utilisateur inexistant.\n";
// }

// // Test 4 : Connexion LDAP avec serveur inaccessible
// echo "\nTest 4 : Connexion LDAP avec serveur inaccessible\n";
// $authModele->ldapHost = "mock://ldap-invalid";
// $_SESSION = [];
// $result = $authModele->verifierUtilisateurLDAP("anyUser", "anyPassword");
// if (!$result && isset($_SESSION['error']) && strpos($_SESSION['error'], 'Échec de connexion au serveur LDAP') !== false) {
//     echo "PASS : Échec attendu avec serveur inaccessible.\n";
// } else {
//     echo "FAIL : Résultat inattendu avec serveur inaccessible.\n";
// }

// // Test 5 : Connexion LDAP avec rôle admin
// echo "\nTest 5 : Connexion LDAP avec rôle admin\n";
// $authModele->ldapHost = "mock://ldap-valid-admin";
// $_SESSION = [];
// $result = $authModele->verifierUtilisateurLDAP("adminUser", "validPassword");
// if ($result && $result['role'] === 'admin') {
//     echo "PASS : Connexion réussie avec utilisateur admin.\n";
// } else {
//     echo "FAIL : Connexion échouée avec utilisateur admin.\n";
// }