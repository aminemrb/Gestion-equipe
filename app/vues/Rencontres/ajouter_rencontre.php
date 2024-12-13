<?php
// Inclure le header
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\RencontreControleur;

// Créez une instance du contrôleur Rencontre
$rencontreControleur = new RencontreControleur();

// Si le formulaire est soumis (méthode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rencontreControleur->ajouter_rencontre(); // Traite la requête POST
}

// Charger le template HTML
$template = file_get_contents(__DIR__ . '/templates/ajouter_rencontre.html');

// Remplacer les placeholders dans le template
$output = str_replace(
    '{{ACTION_URL}}',
    htmlspecialchars($_SERVER['PHP_SELF']),
    $template
);

// Afficher le contenu
echo $output;

// Inclure le footer
include __DIR__ . '/../Layouts/footer.php';
?>
