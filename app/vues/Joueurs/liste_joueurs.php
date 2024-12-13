<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\JoueurControleur;

// Créer une instance du contrôleur
$joueurControleur = new JoueurControleur();

// Récupérer tous les joueurs
$joueurs = $joueurControleur->liste_joueurs();

// Charger le template HTML
$template = file_get_contents(__DIR__ . '/templates/liste_joueurs.html');

// Construire les lignes du tableau
$rows = '';
foreach ($joueurs as $joueur) {
    $rows .= "
    <tr>
        <td>{$joueur['numero_licence']}</td>
        <td>{$joueur['nom']}</td>
        <td>{$joueur['prenom']}</td>
        <td>{$joueur['date_naissance']}</td>
        <td>{$joueur['taille']}</td>
        <td>{$joueur['poids']}</td>
        <td>{$joueur['statut']}</td>
        <td>{$joueur['position_preferee']}</td>
        <td>{$joueur['commentaire']}</td>
        <td>
            <a href=\"" . BASE_URL . "/vues/Joueurs/modifier_joueur.php?numero_licence={$joueur['numero_licence']}\">Modifier</a>
            <a href=\"" . BASE_URL . "/vues/Joueurs/supprimer_joueur.php?numero_licence={$joueur['numero_licence']}\" 
               onclick=\"return confirm('Êtes-vous sûr de vouloir supprimer ce joueur ?');\">Supprimer</a>
        </td>
    </tr>
    ";
}

// Remplacer les placeholders dans le template
$output = str_replace(
    ['{{BASE_URL}}', '{{ROWS}}'],
    [BASE_URL, $rows],
    $template
);

// Afficher le résultat
echo $output;

include __DIR__ . '/../Layouts/footer.php';