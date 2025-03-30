<?php
session_start();
require 'db.php';

$messageErreur = "";
$messageSucces = "";
$apprenants = [];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['filiere'], $_POST['annee'])) {
    $filiere = trim($_POST['filiere']);
    $annee = trim($_POST['annee']);

    if (!empty($filiere) && !empty($annee)) {
        // Requête corrigée avec le nom de table 'apprenant'
        $stmt = $pdo->prepare("
            SELECT 
                id,
                nom, 
                prenom, 
                email,
                filiere, 
                annee_etude,
                matricule
            FROM apprenant
            WHERE filiere = ? AND annee_etude = ?
            ORDER BY nom, prenom
        ");
        $stmt->execute([$filiere, $annee]);
        $apprenants = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($apprenants)) {
            $messageErreur = "Aucun apprenant trouvé pour ces critères.";
        }
    } else {
        $messageErreur = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Apprenants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            display: flex;
            height: 100vh;
            font-family: 'Arial', sans-serif;
            margin: 0;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: #fff;
            height: 100vh;
            padding-top: 30px;
            padding-left: 15px;
            position: fixed;
            left: 0;
            top: 0;
        }
        .sidebar h4 {
            color: #fff;
            margin-bottom: 30px;
            font-size: 20px;
        }
        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            margin: 5px 0;
            border-radius: 4px;
            font-size: 16px;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #495057;
            color: #fff;
        }
        .content {
            flex: 1;
            margin-left: 250px;  /* Espace pour la sidebar */
            padding: 30px;
        }
        .content h2 {
            margin-top: 0;
        }
        .btn-logout {
            background-color: #dc3545;
            color: white;
            border: none;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
        }
        .btn-logout:hover {
            background-color: #c82333;
        }
        /* Pour le contenu de la sidebar */
        .sidebar a i {
            margin-right: 10px;
        }
    </style>
</>
</head>
<body>
<div class="container mt-5">
    <h2>Recherche d'apprenants</h2>

    <!-- Messages d'erreur/succès -->
    <?php if (!empty($messageErreur)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($messageErreur) ?></div>
    <?php endif; ?>
    
    <?php if (!empty($messageSucces)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($messageSucces) ?></div>
    <?php endif; ?>

    <!-- Formulaire de recherche -->
    <form method="POST" class="mb-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="filiere" class="form-label">Filière</label>
                <select class="form-select" id="filiere" name="filiere" required>
                    <option value="">Choisir une filière...</option>
                    <option value="Informatique" <?= ($_POST['filiere'] ?? '') === 'Informatique' ? 'selected' : '' ?>>Informatique</option>
                    <option value="Gestion" <?= ($_POST['filiere'] ?? '') === 'Gestion' ? 'selected' : '' ?>>Gestion</option>
                    <option value="Economie" <?= ($_POST['filiere'] ?? '') === 'Economie' ? 'selected' : '' ?>>Économie</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="annee" class="form-label">Année d'étude</label>
                <select class="form-select" id="annee" name="annee" required>
                    <option value="">Choisir une année...</option>
                    <option value="1" <?= ($_POST['annee'] ?? '') === '1' ? 'selected' : '' ?>>1</option>
                    <option value="2" <?= ($_POST['annee'] ?? '') === '2' ? 'selected' : '' ?>>2</option>
                    <option value="3" <?= ($_POST['annee'] ?? '') === '3' ? 'selected' : '' ?>>3</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </div>
    </form>

    <!-- Résultats -->
    <?php if (!empty($apprenants)): ?>
        <div class="card">
            <div class="card-header">
                <h3>Résultats (<?= count($apprenants) ?> apprenants)</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Filière</th>
                                <th>Année</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($apprenants as $apprenant): ?>
                            <tr>
                                <td><?= htmlspecialchars($apprenant['matricule']) ?></td>
                                <td><?= htmlspecialchars($apprenant['nom']) ?></td>
                                <td><?= htmlspecialchars($apprenant['prenom']) ?></td>
                                <td><?= htmlspecialchars($apprenant['email']) ?></td>
                                <td><?= htmlspecialchars($apprenant['filiere']) ?></td>
                                <td><?= htmlspecialchars($apprenant['annee_etude']) ?></td>
                                <td>
                                    <a href="notes.php?id=<?= $apprenant['id'] ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-journal-text"></i> Notes
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>