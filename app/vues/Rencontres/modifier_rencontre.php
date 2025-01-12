<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\RencontreControleur;

$rencontreControleur = new RencontreControleur();
$id_rencontre = $_GET['id_rencontre'] ?? null;

if (!$id_rencontre) {
    echo "ID de la rencontre non fourni.";
    exit;
}

// Récupérer les informations actuelles de la rencontre
$rencontre = $rencontreControleur->getRencontreById($id_rencontre);

if (!$rencontre) {
    echo "Rencontre non trouvée.";
    exit;
}

// Traitement du formulaire après soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les nouvelles valeurs
    $equipe_adverse = $_POST['equipe_adverse'];
    $date_rencontre = $_POST['date_rencontre'];
    $heure_rencontre = $_POST['heure_rencontre'];
    $lieu = $_POST['lieu'];

    // Vérification que la nouvelle date et heure sont valides
    $currentDateTime = new DateTime();
    $dateTimeRendezvous = new DateTime("$date_rencontre $heure_rencontre");

    if ($dateTimeRendezvous <= $currentDateTime) {
        echo "<p style='color: red;'>Erreur : La date et l'heure de la rencontre doivent être supérieures à la date et l'heure actuelles.</p>";
    } else {
        // Modification de la rencontre si la date et l'heure sont valides
        $rencontreControleur->modifier_rencontre($id_rencontre, $equipe_adverse, $date_rencontre, $heure_rencontre, $lieu);
        echo "<p style='color: green;'>Rencontre modifiée avec succès !</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/formulaire.css">
    <title>Modifier une rencontre</title>
</head>
<body>
<div id="modifier-style">
    <main>
        <h1>Modifier une rencontre</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="equipe_adverse">Équipe Adverse :</label>
                <input type="text" id="equipe_adverse" name="equipe_adverse"
                       value="<?= htmlspecialchars($rencontre['equipe_adverse']) ?>"  pattern="[A-Za-zÀ-ÿ]+" required>
            </div>

            <div class="form-group">
                <label for="date_rencontre">Date de la rencontre :</label>
                <?php
                $minDate = date("Y-m-d");
                ?>
                <input type="date" id="date_rencontre" name="date_rencontre"
                       value="<?= htmlspecialchars($rencontre['date_rencontre']) ?>" required min="<?= $minDate ?>">
            </div>

            <div class="form-group">
                <label for="heure_rencontre">Heure de la rencontre :</label>
                <?php
                $minTime = date("H:i", strtotime("+1 minute"));
                ?>
                <input type="time" id="heure_rencontre" name="heure_rencontre"
                       value="<?= htmlspecialchars($rencontre['heure_rencontre']) ?>" required>
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
