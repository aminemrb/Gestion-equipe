<?php
include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\RencontreControleur;

$rencontreControleur = new RencontreControleur();
$id_rencontre = $_GET['id_rencontre'] ?? null;
$rencontre = $rencontreControleur->ajouter_resultat();

// Load the HTML template
$template = file_get_contents(__DIR__ . '/templates/ajouter_resultat.html');

// Replace placeholders with actual values
$output = str_replace(
    ['{{id_rencontre}}', '{{score_equipe}}', '{{score_adverse}}'],
    [htmlspecialchars($id_rencontre), htmlspecialchars($rencontre['score_equipe'] ?? ''), htmlspecialchars($rencontre['score_adverse'] ?? '')],
    $template
);

// Display the result
echo $output;

include __DIR__ . '/../Layouts/footer.php';
?>