<?php
include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\RencontreControleur;

// Instantiate the controller
$rencontreControleur = new RencontreControleur();

// Get the rencontre ID from the request (e.g., URL parameter)
$id_rencontre = $_GET['id_rencontre'] ?? null;

if ($id_rencontre) {
    // Call the method to get the rencontre details
    $rencontre = $rencontreControleur->modifier_rencontre($id_rencontre);
} else {
    echo "ID de la rencontre non fourni.";
    exit;
}
?>

<main>
    <h1>Modifier la rencontre</h1>

    <?php if ($rencontre): ?>
        <form method="post" action="">
            <label for="equipe_adverse">Équipe Adverse :</label>
            <input type="text" id="equipe_adverse" name="equipe_adverse"
                   value="<?php echo htmlspecialchars($rencontre['equipe_adverse']); ?>" required>

            <label for="date_rencontre">Date de la rencontre :</label>
            <input type="date" id="date_rencontre" name="date_rencontre"
                   value="<?php echo htmlspecialchars($rencontre['date_rencontre']); ?>" required>

            <label for="heure_rencontre">Heure de la rencontre :</label>
            <input type="time" id="heure_rencontre" name="heure_rencontre"
                   value="<?php echo htmlspecialchars($rencontre['heure_rencontre']); ?>" required>

            <label for="lieu">Lieu :</label>
            <select id="lieu" name="lieu" required>
                <option value="Domicile">Domicile</option>
                <option value="Exterieur">Exterieur</option>
            </select>

            <label for="resultat">Résultat :</label>
            <select id="resultat" name="resultat" required>
                <option value="Victoire">Victoire</option>
                <option value="Défaite">Défaite</option>
                <option value="Nul">Nul</option>
                <option value="<Rien>">Rien</option>
            </select>

            <button type="submit">Modifier</button>
        </form>
    <?php else: ?>
        <p>Rencontre non trouvée.</p>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
