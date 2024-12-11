<?php include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\RencontreControleur;
use App\Controleurs\SelectionControleur;

// Créer une instance des contrôleurs
$rencontreControleur = new RencontreControleur();
$selectionControleur = new SelectionControleur();

// Récupérer toutes les rencontres
$rencontres = $rencontreControleur->liste_rencontres();
?>

    <main>
        <h1>Liste des rencontres</h1>

        <a href="<?php echo BASE_URL; ?>/vues/Rencontres/ajouter_rencontre.php">Ajouter une rencontre</a>

        <?php if (empty($rencontres)): ?>
            <p>Aucune rencontre trouvée dans la base de données.</p>
        <?php else: ?>
            <table border="1">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Équipe Adverse</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Lieu</th>
                    <th>Résultat</th>
                    <th>Joueurs Sélectionnés</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rencontres as $rencontre): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($rencontre['id_rencontre']); ?></td>
                        <td><?php echo htmlspecialchars($rencontre['equipe_adverse']); ?></td>
                        <td><?php echo htmlspecialchars($rencontre['date_rencontre']); ?></td>
                        <td><?php echo htmlspecialchars($rencontre['heure_rencontre']); ?></td>
                        <td><?php echo htmlspecialchars($rencontre['lieu']); ?></td>
                        <td><?php echo htmlspecialchars($rencontre['resultat'] ?? 'N/A'); ?></td>
                        <td>
                            <?php
                            $joueurs_selectionnes = $selectionControleur->getJoueursSelectionnes($rencontre['id_rencontre']);
                            if (empty($joueurs_selectionnes)) {
                                echo "Aucun joueur sélectionné";
                            } else {
                                foreach ($joueurs_selectionnes as $joueur) {
                                    echo htmlspecialchars($joueur['nom'] . ' ' . $joueur['prenom']) . '<br>';
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/vues/Rencontres/formulaire_selection.php?id_rencontre=<?php echo $rencontre['id_rencontre']; ?>">Sélection</a>
                            <a href="<?php echo BASE_URL; ?>/vues/Rencontres/modifier_rencontre.php?id_rencontre=<?php echo $rencontre['id_rencontre']; ?>">Modifier</a>
                            <a href="<?php echo BASE_URL; ?>/vues/Rencontres/supprimer_rencontre.php?id_rencontre=<?php echo $rencontre['id_rencontre']; ?>">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>