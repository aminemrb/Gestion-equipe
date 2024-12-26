<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\JoueurControleur;
use App\Controleurs\SelectionControleur;

$selectionControleur = new SelectionControleur();
$joueurControleur = new JoueurControleur();

$id_rencontre = $_GET['id_rencontre'] ?? null;
if (!$id_rencontre) {
    echo "Aucune rencontre sélectionnée.";
    exit;
}

$joueurs_selectionnes = $selectionControleur->getJoueursSelectionnes($id_rencontre);
$postes_fixes = [
    "GB", "DG", "DCG", "DCD", "DD",
    "MD", "MCG", "MCD", "AD", "AG", "BU",
    "R1", "R2", "R3", "R4", "R5"
];

$postes_assignes = array_fill_keys($postes_fixes, null);
foreach ($joueurs_selectionnes as $joueur) {
    $postes_assignes[$joueur['poste']] = $joueur;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postes_postes = $_POST['postes'] ?? [];
    try {
        $selectionControleur->validerSelection($id_rencontre, $postes_postes);
        header("Location: " . $_SERVER['REQUEST_URI']);
        echo "<p>La sélection a été validée avec succès.</p>";
    } catch (\Exception $e) {
        echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

$joueurs = $joueurControleur->liste_joueurs_actifs();

function getPosteClass($poste) {
    $gardien = ["GB"];
    $defenseurs = ["DG", "DCG", "DCD", "DD"];
    $milieux = ["MD", "MCG", "MCD", "AD", "AG"];
    $attaquants = ["BU"];

    if (in_array($poste, $gardien)) return "gardien";
    if (in_array($poste, $defenseurs)) return "defenseur";
    if (in_array($poste, $milieux)) return "milieu";
    if (in_array($poste, $attaquants)) return "attaquant";

    return "remplacant"; // Par défaut aucune classe
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Feuille de Match</title>
    <link rel="stylesheet" href="/football_manager/public/assets/css/selection.css">
</head>
<body>
<h1>Feuille de Match</h1>

<form method="POST">
    <input type="hidden" name="id_rencontre" value="<?php echo htmlspecialchars($id_rencontre); ?>">
    <div class="table-container">
    <table class="table-compo">
        <thead>
        <tr>
            <th>Poste</th>
            <th>Nom</th>
            <th>Prénom</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($postes_fixes as $poste): ?>
            <?php $classe = getPosteClass($poste); ?>
            <tr>
                <td class="<?php echo $classe; ?>"><?php echo htmlspecialchars($poste); ?></td>
                <?php if (isset($postes_assignes[$poste])): ?>
                    <td><?php echo htmlspecialchars($postes_assignes[$poste]['nom'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($postes_assignes[$poste]['prenom'] ?? '-'); ?></td>
                <?php else: ?>
                    <td colspan="2">-</td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <table class="table-selection">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Poste</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($joueurs as $joueur): ?>
            <tr>
                <td><?php echo htmlspecialchars($joueur['nom']); ?></td>
                <td><?php echo htmlspecialchars($joueur['prenom']); ?></td>
                <td>
                    <select name="postes[<?php echo $joueur['numero_licence']; ?>]">
                        <option value="">-- Choisir --</option>
                        <?php foreach ($postes_fixes as $poste): ?>
                            <?php
                            $is_poste_attribue = isset($postes_assignes[$poste]);
                            $is_selected = (isset($postes_assignes[$poste]) && $postes_assignes[$poste]['numero_licence'] == $joueur['numero_licence']);
                            ?>
                            <option value="<?php echo htmlspecialchars($poste); ?>"
                                    <?php if ($is_selected): ?>selected<?php endif; ?>>
                                <?php echo htmlspecialchars($poste); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <br>
    <input type="submit" value="Valider la sélection">
</form>
</body>
</html>
