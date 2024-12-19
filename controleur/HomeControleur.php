<?php

require_once __DIR__ . '/../modele/HomeModele.php';

class HomeControleur {
    private $modele;

    public function __construct() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Constructeur de la classe HomeControleur
        
        Arguments: aucun
        Return: string
        */
        $this->modele = new HomeModele();
    }

    private function formaterValeur($valeur) {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Formater la valeur
        
        Arguments: valeur (int)
        Return: int
        */
        // Si c'est un nombre entier, retourne-le sans modification
        if (is_numeric($valeur) && floor($valeur) == $valeur) {
            return (int) $valeur;
        }
        // Sinon, arrondit à 2 décimales
        return round($valeur, 2);
    }
    
    public function afficherPage() {
        /*
        QUI: Vergeylen Anthony
        QUAND: 18-12-2024
        QUOI: Afficher la page
        
        Arguments: aucun
        Return: vue
        */
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





// // Test 1 : Création de l'objet HomeControleur
// echo "Test 1 : Constructeur\n";
// $controleurTest = new HomeControleur();
// if ($controleurTest) {
//     echo "PASS : Le contrôleur a été créé avec succès.\n";
// } else {
//     echo "FAIL : Le contrôleur n'a pas pu être créé.\n";
// }

// // Test 2 : Vérification de la méthode formaterValeur avec un entier
// echo "\nTest 2 : formaterValeur avec un entier\n";
// $result = $controleurTest->formaterValeur(42);
// if ($result === 42) {
//     echo "PASS : La valeur entière a été correctement formatée.\n";
// } else {
//     echo "FAIL : La valeur entière n'a pas été correctement formatée.\n";
// }

// // Test 3 : Vérification de la méthode formaterValeur avec un flottant
// echo "\nTest 3 : formaterValeur avec un flottant\n";
// $result = $controleurTest->formaterValeur(42.6789);
// if ($result === 42.68) {
//     echo "PASS : La valeur flottante a été correctement arrondie.\n";
// } else {
//     echo "FAIL : La valeur flottante n'a pas été correctement arrondie.\n";
// }

// // Test 4 : Vérification de la méthode formaterValeur avec une chaîne de caractères
// echo "\nTest 4 : formaterValeur avec une chaîne de caractères\n";
// $result = $controleurTest->formaterValeur("test");
// if ($result === 0) {
//     echo "PASS : La chaîne de caractères a été correctement gérée.\n";
// } else {
//     echo "FAIL : La chaîne de caractères n'a pas été correctement gérée.\n";
// }

// // Test 5 : Vérification de afficherPage avec des données simulées
// echo "\nTest 5 : afficherPage avec données simulées\n";
// $controleurTest->modele = new class {
//     public function getWelcomeMessage() {
//         return "Bienvenue !";
//     }

//     public function recupererInformationsGlobales() {
//         return [42, 42.6789, "test"];
//     }

//     public function recupererConsommation30Jours() {
//         return [
//             ['total' => 123.456],
//             ['total' => 789.123]
//         ];
//     }

//     public function recupererProduction30Jours() {
//         return [
//             ['solaire' => 100.5678, 'eolienne' => 200.789],
//             ['solaire' => 300.1234, 'eolienne' => 400.4567]
//         ];
//     }
// };

// ob_start();
// $controleurTest->afficherPage();
// $output = ob_get_clean();
// if (strpos($output, "Bienvenue !") !== false && strpos($output, "42") !== false && strpos($output, "42.68") !== false) {
//     echo "PASS : La page affiche correctement les données formatées.\n";
// } else {
//     echo "FAIL : La page n'affiche pas correctement les données formatées.\n";
// }