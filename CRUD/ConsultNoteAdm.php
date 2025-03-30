<?php
session_start();
require 'db.php';

$etudiants = [];
$matieres = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filtrer'])) {
    $filiere = $_POST['filiere'];
    $annee_etude = $_POST['annee_etude'];
    
    // Récupérer les étudiants correspondants
    $stmt = $pdo->prepare("SELECT * FROM Apprenant WHERE filiere = ? AND annee_etude = ?");
    $stmt->execute([$filiere, $annee_etude]);
    $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer les matières de la filière
    $stmt = $pdo->prepare("SELECT * FROM G_matiere WHERE filiere = ? AND annee_etude = ?");
    $stmt->execute([$filiere, $annee_etude]);
    $matieres = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Étudiants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Consulter les notes des etudiants</h2>
    
    <!-- Formulaire de filtrage -->
    <form method="POST" class="mb-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="filiere" class="form-label">Filière</label>
                <select class="form-select" id="filiere" name="filiere" required>
                    <option value="">Sélectionnez une filière</option>
                    <option value="Informatique">Informatique</option>
                    <option value="Gestion">Gestion</option>
                    <option value="Economie">Économie</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="annee_etude" class="form-label">Année d'étude</label>
                <select class="form-select" id="annee_etude" name="annee_etude" required>
                    <option value="">Sélectionnez une année</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" name="filtrer" class="btn btn-primary">Filtrer</button>
            </div>
        </div>
    </form>

    <!-- Affichage des résultats -->
    <?php if (!empty($etudiants)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2">Matricule</th>
                        <th rowspan="2">Nom</th>
                        <th rowspan="2">Prénom</th>
                        <th colspan="<?= count($matieres) * 3 ?>">Notes par matière</th>
                    </tr>
                    <tr>
                        <?php foreach ($matieres as $matiere): ?>
                            <th colspan="3"><?= htmlspecialchars($matiere['nom_matiere']) ?></th>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <?php foreach ($matieres as $matiere): ?>
                            <th>CC1</th>
                            <th>CC2</th>
                            <th>Moyenne</th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($etudiants as $etudiant): ?>
                        <tr>
                            <td><?= htmlspecialchars($etudiant['matricule']) ?></td>
                            <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                            <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                            
                            <?php 
                            // Récupérer les notes pour chaque matière de cet étudiant
                            $notesParMatiere = [];
                            foreach ($matieres as $matiere) {
                                $stmt = $pdo->prepare("
                                    SELECT note_CC1, note_CC2 
                                    FROM notes_apprenant 
                                    WHERE apprenant_id = ? AND matiere_id = ?
                                ");
                                $stmt->execute([$etudiant['id'], $matiere['id']]);
                                $notes = $stmt->fetch(PDO::FETCH_ASSOC);
                                
                                // Calcul de la moyenne
                                $moyenne = null;
                                if ($notes) {
                                    $noteCC1 = $notes['note_CC1'] ?? 0;
                                    $noteCC2 = $notes['note_CC2'] ?? 0;
                                    if (!is_null($noteCC1) && !is_null($noteCC2)) {
                                        $moyenne = ($noteCC1 + $noteCC2) / 2;
                                    } elseif (!is_null($noteCC1)) {
                                        $moyenne = $noteCC1;
                                    } elseif (!is_null($noteCC2)) {
                                        $moyenne = $noteCC2;
                                    }
                                }
                                
                                $notesParMatiere[$matiere['id']] = [
                                    'note_CC1' => $notes['note_CC1'] ?? null,
                                    'note_CC2' => $notes['note_CC2'] ?? null,
                                    'moyenne' => $moyenne
                                ];
                            }
                            
                            // Afficher les notes et moyennes
                            foreach ($matieres as $matiere): 
                                $notes = $notesParMatiere[$matiere['id']];
                            ?>
                                <td><?= $notes['note_CC1'] ?? '-' ?></td>
                                <td><?= $notes['note_CC2'] ?? '-' ?></td>
                                <td><?= isset($notes['moyenne']) ? number_format($notes['moyenne'], 2) : '-' ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>