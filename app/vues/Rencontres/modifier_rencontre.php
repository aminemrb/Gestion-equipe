<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\RencontreControleur;

$rencontreControleur = new RencontreControleur();
$id_rencontre = $_GET['id_rencontre'] ?? null;

if (!$id_rencontre) {
    echo "ID de la rencontre non fourni.";
    exit;
}

$rencontre = $rencontreControleur->modifier_rencontre($id_rencontre);

if (!$rencontre) {
    echo "Rencontre non trouvée.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/modifier.css">
    <title>Modifier une rencontre</title>
</head>
<body>
<div id="modifier-style">
    <main>
        <h1>Modifier une rencontre</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="equipe_adverse">Équipe Adverse :</label>
                <input type="text" id="equipe_adverse" name="equipe_adverse" value="<?= htmlspecialchars($rencontre['equipe_adverse']) ?>" required>
            </div>

            <div class="form-group">
                <label for="date_rencontre">Date de la rencontre :</label>
                <input type="date" id="date_rencontre" name="date_rencontre" value="<?= htmlspecialchars($rencontre['date_rencontre']) ?>" required>
            </div>

            <div class="form-group">
                <label for="heure_rencontre">Heure de la rencontre :</label>
                <input type="time" id="heure_rencontre" name="heure_rencontre" value="<?= htmlspecialchars($rencontre['heure_rencontre']) ?>" required>
            </div>

            <div class="form-group">
                <label for="lieu">Lieu :</label>
                <select id="lieu" name="lieu" required>
                    <option value="Domicile" <?= $rencontre['lieu'] === 'Domicile' ? 'selected' : '' ?>>Domicile</option>
                    <option value="Exterieur" <?= $rencontre['lieu'] === 'Exterieur' ? 'selected' : '' ?>>Exterieur</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit">Modifier</button>
            </div>
        </form>
    </main>
</div>
</body>
</html>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
