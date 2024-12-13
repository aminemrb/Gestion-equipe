<?php
include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\RencontreControleur;

// Créez une instance du contrôleur
$rencontreControleur = new RencontreControleur();

// Récupérez l'ID de la rencontre depuis l'URL
$id_rencontre = $_GET['id_rencontre'] ?? null;

if (!$id_rencontre) {
    echo "ID de la rencontre non fourni.";
    exit;
}

// Obtenez les détails de la rencontre à modifier
$rencontre = $rencontreControleur->modifier_rencontre($id_rencontre);

if (!$rencontre) {
    echo "Rencontre non trouvée.";
    exit;
}

// Charger le template HTML pour modifier la rencontre
$template = file_get_contents(__DIR__ . '/templates/modifier_rencontre.html');

// Remplacer les placeholders dans le template
$output = str_replace(
    [
        '{{rencontre.equipe_adverse}}',
        '{{rencontre.date_rencontre}}',
        '{{rencontre.heure_rencontre}}',
        '{{selected.domicile}}',
        '{{selected.exterieur}}',
        '{{selected.victoire}}',
        '{{selected.defaite}}',
        '{{selected.nul}}',
        '{{selected.rien}}'
    ],
    [
        htmlspecialchars($rencontre['equipe_adverse']),
        htmlspecialchars($rencontre['date_rencontre']),
        htmlspecialchars($rencontre['heure_rencontre']),
        $rencontre['lieu'] === 'Domicile' ? 'selected' : '',
        $rencontre['lieu'] === 'Exterieur' ? 'selected' : '',
        $rencontre['resultat'] === 'Victoire' ? 'selected' : '',
        $rencontre['resultat'] === 'Défaite' ? 'selected' : '',
        $rencontre['resultat'] === 'Nul' ? 'selected' : '',
        $rencontre['resultat'] === '<Rien>' ? 'selected' : ''
    ],
    $template
);

// Afficher le formulaire modifié
echo $output;

include __DIR__ . '/../Layouts/footer.php';
?>
