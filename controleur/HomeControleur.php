    <?php

    require_once __DIR__ . '/../modele/HomeModele.php';

    class HomeControleur {
        private $modele;

        public function __construct() {
            $this->modele = new HomeModele();
        }

        public function afficherPage() {
            $welcomeMessage = $this->modele->getWelcomeMessage();
            $recupererInformationsGlobales = $this->modele->recupererInformationsGlobales();
            require __DIR__ . '/../vue/home.php';
        }
    }

    // Exécuter le contrôleur
    $controleur = new HomeControleur();
    $controleur->afficherPage();
