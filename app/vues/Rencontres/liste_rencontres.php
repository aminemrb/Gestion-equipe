<?php include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\RencontreControleur;

// Créer une instance du contrôleur
$rencontreControleur = new RencontreControleur();

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
                        <a href="<?php echo BASE_URL; ?>/vues/Rencontres/modifier_rencontre.php?id_rencontre=<?php echo $rencontre['id_rencontre']; ?>">Modifier</a>
                        <a href="<?php echo BASE_URL; ?>/vues/Rencontres/supprimer_rencontre.php?id_rencontre=<?php echo $rencontre['id_rencontre']; ?>"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
