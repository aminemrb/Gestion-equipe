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

// Récupérer les joueurs sélectionnés si une rencontre est spécifiée
$joueurs_selectionnes = $id_rencontre ? $selectionControleur->getJoueursSelectionnes($id_rencontre) : [];
$selectionnes_ids = array_column($joueurs_selectionnes, 'numero_licence');

// Vérifier s'il y a au moins 11 joueurs
if (count($joueurs) < 1) {
    echo "<p>Il faut au moins 11 joueurs pour faire une sélection.</p>";
    exit;
}
?>

    <main>
        <h1>Formulaire de sélection des joueurs</h1>
        <form method="post" action="traiter_selection.php">
            <input type="hidden" name="id_rencontre" value="<?php echo htmlspecialchars($id_rencontre); ?>">
            <fieldset>
                <legend>Sélectionnez les joueurs actifs pour cette rencontre</legend>
                <?php foreach ($joueurs as $joueur): ?>
                    <div>
                        <input type="checkbox" id="joueur_<?php echo $joueur['numero_licence']; ?>" name="joueurs[]" value="<?php echo $joueur['numero_licence']; ?>" <?php echo in_array($joueur['numero_licence'], $selectionnes_ids) ? 'checked' : ''; ?>>
                        <label for="joueur_<?php echo $joueur['numero_licence']; ?>"><?php echo htmlspecialchars($joueur['nom'] . ' ' . $joueur['prenom']); ?></label>
                    </div>
                <?php endforeach; ?>
            </fieldset>
            <button type="submit">Valider la sélection</button>
        </form>
    </main>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>