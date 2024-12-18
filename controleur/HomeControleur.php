<?php

require_once __DIR__ . '/../modele/HomeModele.php';

class HomeControleur {
    private $modele;

    public function __construct() {
        $this->modele = new HomeModele();
    }

    private function formaterValeur($valeur) {
        // Si c'est un nombre entier, retourne-le sans modification
        if (is_numeric($valeur) && floor($valeur) == $valeur) {
            return (int) $valeur;
        }
        // Sinon, arrondit à 2 décimales
        return round($valeur, 2);
    }
    
    public function afficherPage() {
        $welcomeMessage = $this->modele->getWelcomeMessage();
        $recupererInformationsGlobales = $this->modele->recupererInformationsGlobales();
        $consommation30Jours = $this->modele->recupererConsommation30Jours();
        $production30Jours = $this->modele->recupererProduction30Jours();
    
        // Formater les valeurs pour chaque résultat
        $recupererInformationsGlobales = array_map(function($valeur) {
            return $this->formaterValeur($valeur);
        }, $recupererInformationsGlobales);
    
        foreach ($consommation30Jours as &$consommation) {
            $consommation['total'] = $this->formaterValeur($consommation['total']);
        }
    
        foreach ($production30Jours as &$production) {
            $production['solaire'] = $this->formaterValeur($production['solaire']);
            $production['eolienne'] = $this->formaterValeur($production['eolienne']);
        }
    
        require __DIR__ . '/../vue/home.php';
    }
    
}

// Exécuter le contrôleur
$controleur = new HomeControleur();