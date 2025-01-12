<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\JoueurControleur;
use App\Controleurs\SelectionControleur;
use App\Controleurs\RencontreControleur;

$controleurSelection = new SelectionControleur();
$controleurJoueur = new JoueurControleur();
$controleurRencontre = new RencontreControleur();

$idRencontre = $_GET['id_rencontre'] ?? null;
if (!$idRencontre) {
    echo "Aucune rencontre sélectionnée.";
    exit;
}

$rencontre = $controleurRencontre->getRencontreById($idRencontre);
if (!$rencontre) {
    echo "Rencontre non trouvée.";
    exit;
}

$dateRencontre = $rencontre['date_rencontre'];
$heureRencontre = $rencontre['heure_rencontre'];
$matchPasse = estRencontrePasse($dateRencontre, $heureRencontre);

$notesExistantes = $controleurSelection->getNotesByRencontre($idRencontre);
$joueursSelectionnes = $controleurSelection->getJoueursSelectionnes($idRencontre);
$postesFixes = ["GB", "DG", "DCG", "DCD", "DD", "MD", "MCG", "MCD", "AD", "AG", "BU", "R1", "R2", "R3", "R4", "R5"];

$postesAssignes = assignerPostesAuxJoueurs($joueursSelectionnes, $postesFixes);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    traiterDemandePost($controleurSelection, $idRencontre, $postesFixes, $postesAssignes, $notesExistantes, $matchPasse);
}

$joueurs = $controleurJoueur->liste_joueurs_actifs();

// Fonctions utilitaires
function assignerPostesAuxJoueurs($joueursSelectionnes, $postesFixes) {
    $postesAssignes = array_fill_keys($postesFixes, null);
    foreach ($joueursSelectionnes as $joueur) {
        $postesAssignes[$joueur['poste']] = $joueur;
    }
    return $postesAssignes;
}

function traiterDemandePost($controleurSelection, $idRencontre, $postesFixes, $postesAssignes, $notesExistantes, $matchPasse) {
    $postesPostes = $_POST['postes'] ?? [];
    $notes = $_POST['notes'] ?? [];

    try {
        if ($matchPasse) {
            $controleurSelection->updateNotes($idRencontre, $notes);
            header("Location: /football_manager/rencontres");
            exit;
        } else {
            $controleurSelection->validerSelection($idRencontre, $postesPostes);
            header("Location: /football_manager/rencontres");
            exit;
        }
    } catch (\Exception $e) {
        echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

function estRencontrePasse($dateRencontre, $heureRencontre) {
    $currentDateTime = new DateTime();
    $matchDateTime = new DateTime("$dateRencontre $heureRencontre");
    return $matchDateTime < $currentDateTime;
}

function obtenirClassePoste($poste) {
    $classes = [
        "GB" => "gardien",
        "DG" => "defenseur", "DCG" => "defenseur", "DCD" => "defenseur", "DD" => "defenseur",
        "MD" => "milieu", "MCG" => "milieu", "MCD" => "milieu",
        "AD" => "attaquant", "AG" => "attaquant", "BU" => "attaquant"
    ];

    return $classes[$poste] ?? "remplacant";
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
    <?php if (!$matchPasse): ?>
        <p style="text-align: center">Veuillez assigner tous les postes titulaires avant de valider la sélection. Aucun poste de remplaçant n'est obligatoire.</p>
    <?php else: ?>
        <p style="text-align: center">La notation de tous les joueurs n'est pas obligatoire</p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="id_rencontre" value="<?php echo htmlspecialchars($idRencontre); ?>">
        <div class="table-container">
            <table class="table-compo">
                <thead>
                <tr>
                    <th>Poste</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <?php if ($matchPasse): ?>
                        <th>Note</th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($postesFixes as $poste): ?>
                    <?php $classe = obtenirClassePoste($poste); ?>
                    <tr>
                        <td class="<?php echo $classe; ?>"><?php echo htmlspecialchars($poste); ?></td>
                        <?php if (isset($postesAssignes[$poste])): ?>
                            <td><?php echo htmlspecialchars($postesAssignes[$poste]['nom'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($postesAssignes[$poste]['prenom'] ?? '-'); ?></td>
                            <?php if ($matchPasse): ?>
                                <td>
                                    <select name="notes[<?php echo $postesAssignes[$poste]['numero_licence']; ?>]">
                                        <option value="">-- Choisir --</option>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <option value="<?php echo $i; ?>"
                                                <?php if (isset($notesExistantes[$postesAssignes[$poste]['numero_licence']]) && $notesExistantes[$postesAssignes[$poste]['numero_licence']] == $i): ?>
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
            <?php if (!$matchPasse): ?>
                <table class="table-selection">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Position préférée</th>
                        <th>Poste</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($joueurs as $joueur): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($joueur['nom']); ?></td>
                            <td><?php echo htmlspecialchars($joueur['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($joueur['position_preferee']); ?></td>
                            <td>
                                <select class="postes" name="postes[<?php echo $joueur['numero_licence']; ?>]">
                                    <option value="">-- Choisir --</option>
                                    <?php foreach ($postesFixes as $poste): ?>
                                        <?php $classe = obtenirClassePoste($poste); ?>
                                        <?php
                                        $isSelected = (isset($postesAssignes[$poste]) && $postesAssignes[$poste]['numero_licence'] == $joueur['numero_licence']);
                                        ?>
                                        <option class="<?php echo $classe; ?>" value="<?php echo htmlspecialchars($poste); ?>"
                                                <?php if ($isSelected): ?>selected<?php endif; ?>>
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
