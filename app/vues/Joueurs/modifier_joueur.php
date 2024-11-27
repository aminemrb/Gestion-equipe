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
?>

    <main>
        <h1>Modifier le joueur</h1>

        <?php if ($joueur): ?>
            <form method="post" action="">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($joueur['nom']); ?>" required>

                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($joueur['prenom']); ?>" required>

                <label for="date_naissance">Date de naissance:</label>
                <input type="date" id="date_naissance" name="date_naissance" value="<?php echo htmlspecialchars($joueur['date_naissance']); ?>" required>

                <label for="taille">Taille:</label>
                <input type="number" id="taille" name="taille" value="<?php echo htmlspecialchars($joueur['taille']); ?>">

                <label for="poids">Poids:</label>
                <input type="number" id="poids" name="poids" value="<?php echo htmlspecialchars($joueur['poids']); ?>">

                <label for="statut">Statut :</label>
                <select id="statut" name="statut" required>
                    <option value="Actif">Actif</option>
                    <option value="Blessé">Blessé</option>
                    <option value="Suspendu">Suspendu</option>
                    <option value="Absent">Absent</option>
                </select>

                <label for="position_preferee">Position préférée:</label>
                <input type="text" id="position_preferee" name="position_preferee" value="<?php echo htmlspecialchars($joueur['position_preferee']); ?>">

                <label for="commentaire">Commentaire:</label>
                <textarea id="commentaire" name="commentaire"><?php echo htmlspecialchars($joueur['commentaire']); ?></textarea>

                <button type="submit">Modifier</button>
            </form>
        <?php else: ?>
            <p>Joueur non trouvé.</p>
        <?php endif; ?>
    </main>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>