<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\RencontreControleur;
use App\Controleurs\SelectionControleur;

// Créer des instances des contrôleurs
$rencontreControleur = new RencontreControleur();
$selectionControleur = new SelectionControleur();

// Récupérer toutes les rencontres
$rencontres = $rencontreControleur->liste_rencontres();

// Filtrer les rencontres à venir
$upcoming_rencontres = array_filter($rencontres, function($rencontre) {
    $currentDateTime = new DateTime();
    $matchDateTime = new DateTime("{$rencontre['date_rencontre']} {$rencontre['heure_rencontre']}");
    return $matchDateTime > $currentDateTime;
});

// Construire les lignes du tableau
$rows = '';
foreach ($upcoming_rencontres as $rencontre) {
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
        <td>$joueurs</td>
        <td>
            <a href=\"" . BASE_URL . "/vues/Feuille_rencontres/formulaire_selection.php?id_rencontre={$rencontre['id_rencontre']}\">Sélection</a>
        </td>
    </tr>
    ";
}

// Afficher le HTML avec les données dynamiques
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/liste.css">
    <title>Feuille de Rencontre</title>
</head>
<body>
<div id="liste">
    <main>
        <h1>Feuille de Rencontre</h1>
        <table>
            <thead>
            <tr>
                <th>ID Rencontre</th>
                <th>Équipe Adverse</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?= $rows ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>

<?php
include __DIR__ . '/../Layouts/footer.php';
?>
