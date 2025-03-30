<?php
session_start();
require 'db.php';

// Vérifier si l'ID apprenant est passé
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: liste_apprenants.php');
    exit();
}

$apprenant_id = $_GET['id'];

// Récupérer les infos de l'apprenant
$stmt = $pdo->prepare("SELECT * FROM apprenant WHERE id = ?");
$stmt->execute([$apprenant_id]);
$apprenant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$apprenant) {
    die("Apprenant non trouvé");
}

// Récupérer les matières de sa filière et année
$stmt = $pdo->prepare("
    SELECT m.id, m.nom_matiere, n.note_CC1, n.note_CC2
    FROM G_matiere m
    LEFT JOIN notes_apprenant n ON m.id = n.matiere_id AND n.apprenant_id = ?
    WHERE m.filiere = ? AND m.annee_etude = ?
    ORDER BY m.nom_matiere
");
$stmt->execute([$apprenant_id, $apprenant['filiere'], $apprenant['annee_etude']]);
$matieres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire d'ajout/modification de notes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sauvegarder_notes'])) {
    foreach ($_POST['notes'] as $matiere_id => $notes) {
        // Validation des notes
        $note_CC1 = !empty($notes['CC1']) ? floatval($notes['CC1']) : NULL;
        $note_CC2 = !empty($notes['CC2']) ? floatval($notes['CC2']) : NULL;
        
        // Vérifier si l'enregistrement existe déjà
        $stmt = $pdo->prepare("SELECT id FROM notes_apprenant WHERE apprenant_id = ? AND matiere_id = ?");
        $stmt->execute([$apprenant_id, $matiere_id]);
        
        if ($stmt->rowCount() > 0) {
            // Mise à jour
            $stmt = $pdo->prepare("
                UPDATE notes_apprenant 
                SET note_CC1 = ?, note_CC2 = ?
                WHERE apprenant_id = ? AND matiere_id = ?
            ");
        } else {
            // Insertion
            $stmt = $pdo->prepare("
                INSERT INTO notes_apprenant (note_CC1, note_CC2, apprenant_id, matiere_id)
                VALUES (?, ?, ?, ?)
            ");
        }
        
        $stmt->execute([$note_CC1, $note_CC2, $apprenant_id, $matiere_id]);
    }
    
    $_SESSION['message_succes'] = "Notes mises à jour avec succès";
    header("Location: notes.php?id=".$apprenant_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">
    <?php if (isset($_SESSION['message_succes'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message_succes'] ?></div>
        <?php unset($_SESSION['message_succes']); ?>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="h4 mb-0">
                <i class="bi bi-person-vcard"></i> Fiche Apprenant
            </h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nom :</strong> <?= htmlspecialchars($apprenant['nom']) ?></p>
                    <p><strong>Prénom :</strong> <?= htmlspecialchars($apprenant['prenom']) ?></p>
                    <p><strong>Matricule :</strong> <?= htmlspecialchars($apprenant['matricule']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Filière :</strong> <?= htmlspecialchars($apprenant['filiere']) ?></p>
                    <p><strong>Année d'étude :</strong> <?= htmlspecialchars($apprenant['annee_etude']) ?></p>
                    <p><strong>Email :</strong> <?= htmlspecialchars($apprenant['email']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h2 class="h4 mb-0">
                <i class="bi bi-journal-bookmark"></i> Notes par matière
            </h2>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Matière</th>
                                <th>Note CC1</th>
                                <th>Note CC2</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($matieres as $matiere): ?>
                            <tr>
                                <td><?= htmlspecialchars($matiere['nom_matiere']) ?></td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="20" 
                                           class="form-control" 
                                           name="notes[<?= $matiere['id'] ?>][CC1]"
                                           value="<?= $matiere['note_CC1'] ?? '' ?>">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="20" 
                                           class="form-control" 
                                           name="notes[<?= $matiere['id'] ?>][CC2]"
                                           value="<?= $matiere['note_CC2'] ?? '' ?>">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-graph-up"></i> Statistiques
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button type="submit" name="sauvegarder_notes" class="btn btn-success">
                        <i class="bi bi-save"></i> Enregistrer toutes les modifications
                    </button>
                    <a href="liste_apprenants.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Retour à la liste
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>