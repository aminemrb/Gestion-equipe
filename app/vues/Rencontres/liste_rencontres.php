<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\RencontreControleur;
use App\Controleurs\SelectionControleur;

// Créer des instances des contrôleurs
$rencontreControleur = new RencontreControleur();
$selectionControleur = new SelectionControleur();

// Récupérer toutes les rencontres
$rencontres = $rencontreControleur->liste_rencontres();

// Charger le template HTML
$template = file_get_contents(__DIR__ . '/templates/liste_rencontres.html');

// Construire les lignes du tableau
$rows = '';
foreach ($rencontres as $rencontre) {
    // Récupérer les joueurs sélectionnés pour cette rencontre
    $joueurs_selectionnes = $selectionControleur->getJoueursSelectionnes($rencontre['id_rencontre']);
    $joueurs = empty($joueurs_selectionnes)
        ? "Aucun joueur sélectionné"
        : implode('<br>', array_map(
            fn($joueur) => htmlspecialchars($joueur['nom'] . ' ' . $joueur['prenom']),
            $joueurs_selectionnes
        ));

    // Ajouter la ligne au tableau
    $rows .= "
    <tr>
        <td>{$rencontre['id_rencontre']}</td>
        <td>{$rencontre['equipe_adverse']}</td>
        <td>{$rencontre['date_rencontre']}</td>
        <td>{$rencontre['heure_rencontre']}</td>
        <td>{$rencontre['lieu']}</td>
        <td>" . ($rencontre['resultat'] ?? 'N/A') . "</td>
        <td>$joueurs</td>
        <td>
            <a href=\"" . BASE_URL . "/vues/Rencontres/formulaire_selection.php?id_rencontre={$rencontre['id_rencontre']}\">Sélection</a>
            <a href=\"" . BASE_URL . "/vues/Rencontres/modifier_rencontre.php?id_rencontre={$rencontre['id_rencontre']}\">Modifier</a>
            <a href=\"" . BASE_URL . "/vues/Rencontres/supprimer_rencontre.php?id_rencontre={$rencontre['id_rencontre']}\" 
               class=\"btn-supprimer\" onclick=\"return confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?');\">Supprimer</a>
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
?>
