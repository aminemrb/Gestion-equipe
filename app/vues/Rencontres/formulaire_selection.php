<?php
include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\SelectionControleur;
use App\Controleurs\JoueurControleur;

// Créer une instance des contrôleurs
$selectionControleur = new SelectionControleur();
$joueurControleur = new JoueurControleur();

// Récupérer l'ID de la rencontre
$id_rencontre = $_GET['id_rencontre'] ?? null;

// Récupérer les joueurs actifs
$joueurs = $joueurControleur->liste_joueurs_actifs();

// Récupérer les joueurs sélectionnés pour cette rencontre
$joueurs_selectionnes = $id_rencontre ? $selectionControleur->getJoueursSelectionnes($id_rencontre) : [];
$selectionnes_ids = array_column($joueurs_selectionnes, 'numero_licence');

// Vérifier s'il y a suffisamment de joueurs
if (count($joueurs) < 1) {
    echo "<p>Il faut au moins 11 joueurs pour faire une sélection.</p>";
    exit;
}

// Charger le template HTML
$template = file_get_contents(__DIR__ . '/templates/formulaire_selection.html');

// Construire les cases à cocher des joueurs
$joueurs_html = '';
foreach ($joueurs as $joueur) {
    $checked = in_array($joueur['numero_licence'], $selectionnes_ids) ? 'checked' : '';
    $joueurs_html .= "
        <div>
            <input type=\"checkbox\" id=\"joueur_{$joueur['numero_licence']}\" name=\"joueurs[]\" value=\"{$joueur['numero_licence']}\" $checked>
            <label for=\"joueur_{$joueur['numero_licence']}\">" . htmlspecialchars($joueur['nom'] . ' ' . $joueur['prenom']) . "</label>
        </div>
    ";
}

// Remplacer les placeholders dans le template
$output = str_replace(
    ['{{id_rencontre}}', '{{joueurs}}'],
    [htmlspecialchars($id_rencontre), $joueurs_html],
    $template
);

// Afficher le résultat
echo $output;

include __DIR__ . '/../Layouts/footer.php';
?>
