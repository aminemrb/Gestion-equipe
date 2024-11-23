<?php include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\JoueurControleur;

// Créer une instance du contrôleur
$joueurControleur = new JoueurControleur();

// Récupérer tous les joueurs
$joueurs = $joueurControleur->liste_joueurs();
?>

<main>
    <h1>Liste des joueurs</h1>

    <a href="<?php echo BASE_URL; ?>/vues/Joueurs/ajouter_joueur.php">Ajouter un joueur</a>

    <?php if (empty($joueurs)): ?>
        <p>Aucun joueur trouvé dans la base de données.</p>
    <?php else: ?>
        <table border="1">
            <thead>
            <tr>
                <th>Numéro de Licence</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date de Naissance</th>
                <th>Taille</th>
                <th>Poids</th>
                <th>Statut</th>
                <th>Position Préférée</th>
                <th>Commentaire</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($joueurs as $joueur): ?>
                <tr>
                    <td><?php echo htmlspecialchars($joueur['numero_licence']); ?></td>
                    <td><?php echo htmlspecialchars($joueur['nom']); ?></td>
                    <td><?php echo htmlspecialchars($joueur['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($joueur['date_naissance']); ?></td>
                    <td><?php echo htmlspecialchars($joueur['taille']); ?></td>
                    <td><?php echo htmlspecialchars($joueur['poids']); ?></td>
                    <td><?php echo htmlspecialchars($joueur['statut']); ?></td>
                    <td><?php echo htmlspecialchars($joueur['position_preferee']); ?></td>
                    <td><?php echo htmlspecialchars($joueur['commentaire']); ?></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/vues/Joueurs/modifier_joueur.php?numero_licence=<?php echo $joueur['numero_licence']; ?>">Modifier</a>
                        <a href="<?php echo BASE_URL; ?>/vues/Joueurs/supprimer_joueur.php?numero_licence=<?php echo $joueur['numero_licence']; ?>
                        " onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce joueur ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
