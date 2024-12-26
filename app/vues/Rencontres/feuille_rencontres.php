<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\JoueurControleur;
use App\Controleurs\SelectionControleur;
use App\Controleurs\RencontreControleur;

$selectionControleur = new SelectionControleur();
$joueurControleur = new JoueurControleur();
$rencontreControleur = new RencontreControleur();

$id_rencontre = $_GET['id_rencontre'] ?? null;
if (!$id_rencontre) {
    echo "Aucune rencontre sélectionnée.";
    exit;
}

$rencontre = $rencontreControleur->getRencontreById($id_rencontre);
if (!$rencontre) {
    echo "Rencontre non trouvée.";
    exit;
}

$date_rencontre = $rencontre['date_rencontre'];
$heure_rencontre = $rencontre['heure_rencontre'];
$matchPassed = estRencontrePasser($date_rencontre, $heure_rencontre);

$notes_existantes = $selectionControleur->getNotesByRencontre($id_rencontre);
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
    echo $_POST['postes'];
    $notes = $_POST['notes'] ?? [];

    try {
        // Enregistre les notes
        if (!$matchPassed) { // On enregistre les notes uniquement après le match
            $poste_titulaires = ["GB", "DG", "DCG", "DCD", "DD", "MD", "MCG", "MCD", "AD", "AG", "BU"];
            foreach ($poste_titulaires as $poste) {
                if (isset($postes_assignes[$poste]) && empty($notes[$postes_assignes[$poste]['numero_licence']])) {
                    throw new \Exception("Tous les joueurs titulaires doivent être notés.");
                }
            }
            $selectionControleur->updateNotes($id_rencontre, $notes);
            header("Location: " . $_SERVER['REQUEST_URI']);

        }
        if ($matchPassed) {
            // Valide la sélection
            $selectionControleur->validerSelection($id_rencontre, $postes_postes);
            // Recharge la page après la mise à jour
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
    } catch (\Exception $e) {
        echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}


$joueurs = $joueurControleur->liste_joueurs_actifs();

function getPosteClass($poste) {
    $gardien = ["GB"];
    $defenseurs = ["DG", "DCG", "DCD", "DD"];
    $milieux = ["MD", "MCG", "MCD"];
    $attaquants = ["AD", "AG", "BU"];

    if (in_array($poste, $gardien)) return "gardien";
    if (in_array($poste, $defenseurs)) return "defenseur";
    if (in_array($poste, $milieux)) return "milieu";
    if (in_array($poste, $attaquants)) return "attaquant";

    return "remplacant"; // Par défaut aucune classe
}

function estRencontrePasser($date_rencontre, $heure_rencontre) {
    $currentDateTime = new DateTime();
    $matchDateTime = new DateTime("$date_rencontre $heure_rencontre");
    return $matchDateTime > $currentDateTime;
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
    <main id="fdm">
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
                    <?php if (!$matchPassed): ?>
                        <th>Note</th>
                    <?php endif; ?>
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
                            <?php if (!$matchPassed): ?>
                                <td>
                                    <select name="notes[<?php echo $postes_assignes[$poste]['numero_licence']; ?>]">
                                        <option value="">-- Choisir --</option>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <option value="<?php echo $i; ?>"
                                                <?php if (isset($notes_existantes[$postes_assignes[$poste]['numero_licence']]) && $notes_existantes[$postes_assignes[$poste]['numero_licence']] == $i): ?>
                                                    selected
                                                <?php endif; ?>>
                                                <?php echo str_repeat("★", $i); ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                            <?php endif; ?>
                        <?php else: ?>
                            <td colspan="2">-</td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($matchPassed): ?>
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
                                <select class="postes" name="postes[<?php echo $joueur['numero_licence']; ?>]">
                                    <option value="">-- Choisir --</option>
                                    <?php foreach ($postes_fixes as $poste): ?>
                                        <?php $classe = getPosteClass($poste); ?>
                                        <?php
                                        $is_selected = (isset($postes_assignes[$poste]) && $postes_assignes[$poste]['numero_licence'] == $joueur['numero_licence']);
                                        ?>
                                        <option class="<?php echo $classe; ?>" value="<?php echo htmlspecialchars($poste); ?>"
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
            <?php endif; ?>
        </div>
        <br>
        <input type="submit" value="Valider la sélection">
    </form>
    </main>
    </body>
    </html>

