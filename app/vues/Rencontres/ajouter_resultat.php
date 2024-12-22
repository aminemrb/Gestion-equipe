<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\RencontreControleur;

// Initialisation
$rencontreControleur = new RencontreControleur();
$id_rencontre = $_GET['id_rencontre'] ?? null;

// Vérifie si l'ID est fourni
if (!$id_rencontre) {
    echo "<p>ID de la rencontre non fourni.</p>";
    include __DIR__ . '/../Layouts/footer.php';
    exit;
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les scores depuis le formulaire
    $score_equipe = $_POST['score_equipe'] ?? null;
    $score_adverse = $_POST['score_adverse'] ?? null;

    // Validation des entrées
    if (is_numeric($score_equipe) && is_numeric($score_adverse)) {
        // Ajouter ou mettre à jour le résultat
        $rencontreControleur->ajouter_resultat($id_rencontre, $score_equipe, $score_adverse);
        echo "<p>Résultat ajouté avec succès.</p>";
    } else {
        echo "<p>Veuillez entrer des scores valides.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/ajouter.css">
    <title>Ajouter Résultat</title>
</head>
<body>
<main>
    <h1>Ajouter Résultat</h1>
    <form method="post" action="">
        <!-- Champ caché pour l'ID de la rencontre -->
        <input type="hidden" name="id_rencontre" value="<?= htmlspecialchars($id_rencontre) ?>">

        <!-- Champ pour le score de l'équipe -->
        <div>
            <label for="score_equipe">Score de notre équipe:</label>
            <input type="number" id="score_equipe" name="score_equipe" min="0" required>
        </div>

        <!-- Champ pour le score adverse -->
        <div>
            <label for="score_adverse">Score de l'équipe adverse:</label>
            <input type="number" id="score_adverse" name="score_adverse" min="0" required>
        </div>

        <!-- Bouton de soumission -->
        <button type="submit">Ajouter Résultat</button>
    </form>
</main>
</body>
</html>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
