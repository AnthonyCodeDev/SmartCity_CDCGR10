<?php
// Inclure les fichiers nécessaires de Dompdf
require_once __DIR__ . '/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

function fetchPageContent($url) {
    // Utilise cURL pour récupérer le contenu de la page
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $output = curl_exec($ch);

    if (curl_errno($ch)) {
        die('Erreur cURL : ' . curl_error($ch));
    }

    curl_close($ch);
    return $output;
}

try {
    // URL de la page à convertir en PDF
    $url = "http://localhost/data?quartier=1";

    // Récupération du contenu HTML de la page
    $htmlContent = fetchPageContent($url);

    // Initialisation de Dompdf
    $dompdf = new Dompdf();

    // Charger le contenu HTML dans Dompdf
    $dompdf->loadHtml($htmlContent);

    // (Optionnel) Configurer la taille et l'orientation de la page
    $dompdf->setPaper('A4', 'portrait');

    // Rendre le HTML en PDF
    $dompdf->render();

    // Télécharger le PDF généré
    $dompdf->stream("data_quartier_1.pdf", ["Attachment" => false]);
} catch (Exception $e) {
    echo "Erreur lors de la génération du PDF : " . $e->getMessage();
}
