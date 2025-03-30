<?php
session_start();
require 'db.php';

// Vérifier si l'étudiant est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer l'ID de l'étudiant depuis la session
$etudiant_id = $_SESSION['user_id'];

// Récupérer les informations de l'étudiant
try {
    $stmt_etudiant = $pdo->prepare("SELECT * FROM Apprenant WHERE id = ?");
    $stmt_etudiant->execute([$etudiant_id]);
    $etudiant = $stmt_etudiant->fetch(PDO::FETCH_ASSOC);
    
    if (!$etudiant) {
        die("Étudiant non trouvé");
    }
    
    $filiere_etudiant = $etudiant['filiere'];
    $annee_etudiant = $etudiant['annee_etude'];
    
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données étudiant: " . $e->getMessage());
}

// Récupérer les matières de la filière et année de l'étudiant avec les notes
try {
    $stmt_matieres = $pdo->prepare("
        SELECT m.id, m.nom_matiere, n.note_CC1, n.note_CC2
        FROM G_matiere m
        LEFT JOIN notes_apprenant n ON m.id = n.matiere_id AND n.apprenant_id = ?
        WHERE m.filiere = ? AND m.annee_etude = ?
        ORDER BY m.nom_matiere
    ");
    $stmt_matieres->execute([$etudiant_id, $filiere_etudiant, $annee_etudiant]);
    $matieres = $stmt_matieres->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Erreur lors de la récupération des matières: " . $e->getMessage());
}

// Calculer la moyenne générale
$moyenne_generale = null;
$total_notes = 0;
$nombre_notes = 0;

foreach ($matieres as $matiere) {
    if ($matiere['note_CC1'] !== null) {
        $total_notes += $matiere['note_CC1'];
        $nombre_notes++;
    }
    if ($matiere['note_CC2'] !== null) {
        $total_notes += $matiere['note_CC2'];
        $nombre_notes++;
    }
}

if ($nombre_notes > 0) {
    $moyenne_generale = round($total_notes / $nombre_notes, 2);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Notes - <?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header-card {
            background-color: #4e73df;
            color: white;
        }
        .matiere-card {
            border-left: 4px solid #4e73df;
            margin-bottom: 15px;
            transition: transform 0.2s;
        }
        .matiere-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .note-badge {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
        .sidebar {
            background-color: #343a40;
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .nav-link {
            color: rgba(255,255,255,.5);
        }
        .nav-link:hover, .nav-link.active {
            color: white;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="d-flex flex-column align-items-center py-4">
            <h4>Dashboard Étudiant</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="listeMatiereNoteEtu.php">
                    <i class="bi bi-journal-bookmark"></i> Mes Notes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-calendar-event"></i> Emploi du temps
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-person"></i> Mon Profil
                </a>
            </li>
            <li class="nav-item mt-4">
                <a class="nav-link text-danger" href="logout.php">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- En-tête avec infos étudiant -->
        <div class="card header-card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2><?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></h2>
                        <p class="mb-0">Matricule: <?= htmlspecialchars($etudiant['matricule']) ?></p>
                    </div>
                    <div class="text-end">
                        <h5>Filière: <?= htmlspecialchars($filiere_etudiant) ?></h5>
                        <p class="mb-0">Année d'étude: <?= htmlspecialchars($annee_etudiant) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Affichage des notes -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-journal-bookmark"></i> Mes Notes
                </h5>
            </div>
            <div class="card-body">
                <?php if (count($matieres) > 0): ?>
                    <div class="row">
                        <?php foreach ($matieres as $matiere): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card matiere-card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($matiere['nom_matiere']) ?></h5>
                                        
                                        <div class="d-flex justify-content-between mt-3">
                                            <div>
                                                <h6>Note CC1:</h6>
                                                <span class="badge bg-primary note-badge">
                                                    <?= $matiere['note_CC1'] !== null ? htmlspecialchars($matiere['note_CC1']) : 'N/A' ?>
                                                </span>
                                            </div>
                                            <div>
                                                <h6>Note CC2:</h6>
                                                <span class="badge bg-primary note-badge">
                                                    <?= $matiere['note_CC2'] !== null ? htmlspecialchars($matiere['note_CC2']) : 'N/A' ?>
                                                </span>
                                            </div>
                                            <div>
                                                <h6>Moyenne:</h6>
                                                <?php
                                                $moyenne_matiere = null;
                                                if ($matiere['note_CC1'] !== null && $matiere['note_CC2'] !== null) {
                                                    $moyenne_matiere = round(($matiere['note_CC1'] + $matiere['note_CC2']) / 2, 2);
                                                } elseif ($matiere['note_CC1'] !== null) {
                                                    $moyenne_matiere = $matiere['note_CC1'];
                                                } elseif ($matiere['note_CC2'] !== null) {
                                                    $moyenne_matiere = $matiere['note_CC2'];
                                                }
                                                ?>
                                                <span class="badge bg-success note-badge">
                                                    <?= $moyenne_matiere !== null ? htmlspecialchars($moyenne_matiere) : 'N/A' ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($moyenne_generale !== null): ?>
                        <div class="alert alert-info mt-4">
                            <h4 class="alert-heading">Moyenne Générale</h4>
                            <p class="mb-0">Votre moyenne générale est de <strong><?= $moyenne_generale ?>/20</strong></p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-warning">
                        Aucune matière trouvée pour votre filière et année d'étude.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>