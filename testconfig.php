<?php
$ldapHost = 'ldap://192.168.100.2'; // URL de ton serveur LDAP
$ldapBaseDn = 'dc=windows_serv_commun'; // Base DN
$ldapUser = 'tom.deneyer'; // Nom d'utilisateur
$ldapPassword = 'Test123'; // Mot de passe

// Connexion au serveur LDAP
$ldapConn = ldap_connect($ldapHost);

if ($ldapConn) {
    // Option pour utiliser LDAP version 3
    ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);

    // Tentative de connexion
    $ldapBind = ldap_bind($ldapConn, "cn=$ldapUser,$ldapBaseDn", $ldapPassword);

    if ($ldapBind) {
        echo "Connecté avec succès ! Utilisateur : $ldapUser";
    } else {
        echo "Échec de la connexion.";
    }

    // Fermeture de la connexion
    ldap_unbind($ldapConn);
} else {
    echo "Impossible de se connecter au serveur LDAP.";
}
?>
