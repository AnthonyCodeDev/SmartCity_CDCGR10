<?php
// Inclure Dompdf depuis votre dossier local
require_once __DIR__ . '/dompdf/autoload.inc.php';
require_once __DIR__ . '/config.php'; // Charger la configuration
require_once __DIR__ . '/_connexionBD.php'; // Connexion à la base de données

use Dompdf\Dompdf;
use Dompdf\Options;

function fetchPageContent($url) {
    // Utiliser cURL pour récupérer le contenu de la page
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
    $url = TOTAL_URL."generationPDF";

    // Récupération du contenu HTML de la page
    $htmlContent = fetchPageContent($url);

    // Remplacer les chemins relatifs par des chemins absolus
    $htmlContent = str_replace(
        'href="/',
        'href="'.TOTAL_URL,
        $htmlContent
    );
    $htmlContent = str_replace(
        'src="/',
        'src="'.TOTAL_URL,
        $htmlContent
    );

    // Configuration des options de Dompdf
    $options = new Options();
    $options->set('isRemoteEnabled', true); // Autorise les fichiers externes
    $options->set('defaultFont', 'DejaVu Sans'); // Définit une police par défaut

    // Initialisation de Dompdf avec les options
    $dompdf = new Dompdf($options);

    // Charger le contenu HTML dans Dompdf
    $dompdf->loadHtml($htmlContent);

    // Configurer la taille et l'orientation de la page
    $dompdf->setPaper('A4', 'portrait');

    // Rendre le HTML en PDF
    $dompdf->render();

    // Générer un nom de fichier dynamique
    $timestamp = date('d-m-Y-H-i');
    $fileName = 'rapportGlobal-' . $timestamp . '.pdf';
    $filePath = __DIR__ . '/../assets/rapports/' . $fileName;

    // Enregistrer le fichier PDF dans le répertoire spécifié
    file_put_contents($filePath, $dompdf->output());

    // Insérer un enregistrement dans la table energetic_report
    $dateRapport = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("
        INSERT INTO energetic_report (id_rapport, rapport_type, date_rapport, chemin_access)
        VALUES (:id_rapport, :rapport_type, :date_rapport, :chemin_access)
    ");
    $stmt->execute([
        ':id_rapport' => $fileName,
        ':rapport_type' => 0,
        ':date_rapport' => $dateRapport,
        ':chemin_access' => $fileName, // Nom du fichier uniquement
    ]);

    echo "Rapport enregistré avec succès : " . basename($filePath);

    // Rediriger vers le fichier PDF généré
    header('Location: ' . TOTAL_URL . '' . $fileName);
} catch (Exception $e) {
    echo "Erreur lors de la génération du PDF : " . $e->getMessage();
}
