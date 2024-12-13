<?php
include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\JoueurControleur;

// Instantiate the controller
$joueurControleur = new JoueurControleur();

// Get the numero_licence from the request (e.g., URL parameter)
$numero_licence = $_GET['numero_licence'] ?? null;

if ($numero_licence) {
    // Call the method to get the player details
    $joueur = $joueurControleur->modifier_joueur($numero_licence);
} else {
    echo "Numéro de licence non fourni.";
    exit;
}

// Charger le template HTML pour modifier le joueur
$template = file_get_contents(__DIR__ . '/templates/modifier_joueur.html');

// Remplacer les placeholders dans le template
$output = str_replace(
    ['{{joueur.nom}}', '{{joueur.prenom}}', '{{joueur.date_naissance}}', '{{joueur.taille}}', '{{joueur.poids}}',
        '{{joueur.statut}}', '{{joueur.position_preferee}}', '{{joueur.commentaire}}'],
    [
        htmlspecialchars($joueur['nom']),
        htmlspecialchars($joueur['prenom']),
        htmlspecialchars($joueur['date_naissance']),
        htmlspecialchars($joueur['taille']),
        htmlspecialchars($joueur['poids']),
        htmlspecialchars($joueur['statut']),
        htmlspecialchars($joueur['position_preferee']),
        htmlspecialchars($joueur['commentaire'])
    ],
    $template
);

// Afficher le résultat
echo $output;

include __DIR__ . '/../Layouts/footer.php';
?>
