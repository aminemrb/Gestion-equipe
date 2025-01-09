<?php
// Inclure le header
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\RencontreControleur;

// Créer une instance du contrôleur
$rencontreControleur = new RencontreControleur();

// Vérifier si un ID de rencontre est passé dans l'URL
$id_rencontre = $_GET['id_rencontre'] ?? null;
if (!$id_rencontre) {
    echo "ID de la rencontre manquant.";
    include __DIR__ . '/../Layouts/footer.php';
    exit;
}

// Récupérer les informations de la rencontre via la méthode ajouter_resultat
$rencontre = $rencontreControleur->ajouter_resultat(); // Appel de la méthode

// Si aucune rencontre n'a été trouvée, afficher un message d'erreur
if (!$rencontre) {
    echo "Rencontre non trouvée.";
    include __DIR__ . '/../Layouts/footer.php';
    exit;
}

// Récupérer les scores actuels (s'ils existent)
$score_equipe = $rencontre['score_equipe'] ?? null;
$score_adverse = $rencontre['score_adverse'] ?? null;

// Traiter la soumission du formulaire pour ajouter ou modifier les résultats
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Appeler la méthode ajouter_resultat pour mettre à jour les résultats
    $rencontreControleur->ajouter_resultat(); // Cette méthode gère l'ajout du résultat
    echo "<p>Résultat ajouté avec succès.</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/formulaire.css">
    <title>Ajouter Résultat</title>
</head>
<body>
<main>
    <h1>Résultat</h1>
    <form method="post" action="">
        <!-- Champ caché pour l'ID de la rencontre -->
        <input type="hidden" name="id_rencontre" value="<?= htmlspecialchars($id_rencontre) ?>">

        <!-- Champ pour le score de l'équipe -->
        <div>
            <label for="score_equipe">Score de notre équipe:</label>
            <input type="number" id="score_equipe" name="score_equipe" min="0" value="<?= $score_equipe !== null ? htmlspecialchars($score_equipe) : '' ?>" required>
        </div>

        <!-- Champ pour le score adverse -->
        <div>
            <label for="score_adverse"> Score <?=htmlspecialchars($rencontre['equipe_adverse']) ?></label>
            <input type="number" id="score_adverse" name="score_adverse" min="0" value="<?= $score_adverse !== null ? htmlspecialchars($score_adverse) : '' ?>" required>
        </div>

        <!-- Bouton de soumission -->
        <button type="submit">Ajouter Résultat</button>
    </form>
</main>
</body>
</html>

<?php
// Inclure le footer
include __DIR__ . '/../Layouts/footer.php';
?>
