<?php
session_start();
require 'db.php';

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_matiere'])) {
    $nom_matiere = htmlspecialchars(trim($_POST['nom_matiere']));
    $filiere = htmlspecialchars(trim($_POST['filiere']));
    $niveau = htmlspecialchars(trim($_POST['niveau']));

    try {
        $stmt = $pdo->prepare("INSERT INTO G_matiere (nom_matiere, filiere, annee_etude) VALUES (?, ?, ?)");
        $stmt->execute([$nom_matiere, $filiere, $niveau]);
        $_SESSION['message'] = "Matière ajoutée avec succès!";
        header("Location: gestion_matieres.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['erreur'] = "Erreur: " . $e->getMessage();
    }
}

// Récupérer toutes les filières distinctes
try {
    $stmt_filieres = $pdo->query("SELECT DISTINCT filiere FROM G_matiere ORDER BY filiere");
    $filieres = $stmt_filieres->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des filières: " . $e->getMessage());
}

// Récupérer les matières groupées par filière
$matieres_par_filiere = [];
try {
    $stmt_matieres = $pdo->query("SELECT * FROM G_matiere ORDER BY filiere, annee_etude, nom_matiere");
    while ($matiere = $stmt_matieres->fetch(PDO::FETCH_ASSOC)) {
        $filiere = $matiere['filiere'];
        if (!isset($matieres_par_filiere[$filiere])) {
            $matieres_par_filiere[$filiere] = [];
        }
        $matieres_par_filiere[$filiere][] = $matiere;
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération des matières: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Matières</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .filiere-card {
            border-left: 4px solid #4e73df;
            margin-bottom: 20px;
        }
        .matiere-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .matiere-item:last-child {
            border-bottom: none;
        }
        .niveau-badge {
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1 class="text-center mb-4">Gestion des Matières par Filière</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['erreur'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['erreur'] ?></div>
            <?php unset($_SESSION['erreur']); ?>
        <?php endif; ?>

        <!-- Formulaire d'ajout -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Ajouter une nouvelle matière</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="nom_matiere" class="form-label">Nom de la matière</label>
                                <input type="text" class="form-control" id="nom_matiere" name="nom_matiere" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="filiere" class="form-label">Filière</label>
                                <input type="text" class="form-control" id="filiere" name="filiere" list="filieres-list" required>
                                <datalist id="filieres-list">
                                    <?php foreach ($filieres as $f): ?>
                                        <option value="<?= htmlspecialchars($f['filiere']) ?>">
                                    <?php endforeach; ?>
                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="niveau" class="form-label">Niveau</label>
                                <select class="form-select" id="niveau" name="niveau" required>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" name="ajouter_matiere" class="btn btn-success w-100">
                                <i class="bi bi-plus-lg"></i> Ajouter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Affichage des matières par filière -->
        <div class="row">
            <?php foreach ($matieres_par_filiere as $filiere => $matieres): ?>
                <div class="col-md-6">
                    <div class="card filiere-card mb-4">
                        <div class="card-header bg-light">
                            <h4 class="mb-0"><?= htmlspecialchars($filiere) ?></h4>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <?php foreach ($matieres as $matiere): ?>
                                    <li class="list-group-item matiere-item d-flex justify-content-between align-items-center">
                                        <span><?= htmlspecialchars($matiere['nom_matiere']) ?></span>
                                        <span class="badge bg-primary niveau-badge">
                                            <?= htmlspecialchars($matiere['annee_etude']) ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>