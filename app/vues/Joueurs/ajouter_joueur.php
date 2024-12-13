<?php
// Inclure le header
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\JoueurControleur;

// Créer une instance du contrôleur
$joueurControleur = new JoueurControleur();

// Traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Appeler la méthode du contrôleur pour ajouter un joueur
    $joueurControleur->ajouter_joueur($_POST);
}

// Charger le template HTML du formulaire
$template = file_get_contents(__DIR__ . '/templates/ajouter_joueur.html');

// Remplacer les placeholders si nécessaire (par exemple {{BASE_URL}})
$output = str_replace(
    '{{BASE_URL}}',
    BASE_URL,
    $template
);

// Afficher le résultat
echo $output;

// Inclure le footer
include __DIR__ . '/../Layouts/footer.php';
?>
