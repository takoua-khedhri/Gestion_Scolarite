<?php
session_start();
require 'db.php';


// Fonction pour valider les données
function validateInput($data) {
    return htmlspecialchars(trim($data));
}

// Traitement du formulaire
try {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

        // Récupération des données POST
        $matricule = validateInput($_POST['matricule']);
        $nom = validateInput($_POST['nom']);
        $prenom = validateInput($_POST['prenom']);
        $email = validateInput($_POST['email']);
        $telephone = validateInput($_POST['telephone']);
        $adresse = validateInput($_POST['adresse']);
        $genre = validateInput($_POST['genre']);
        $filiere = validateInput($_POST['filiere']);
        $annee_etude = validateInput($_POST['annee_etude']);

        if ($action === 'add') {
            // Ajout d'un nouvel étudiant
            $stmt = $pdo->prepare("INSERT INTO Apprenant (matricule, nom, prenom, email, telephone, adresse, genre, filiere, annee_etude) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$matricule, $nom, $prenom, $email, $telephone, $adresse, $genre, $filiere, $annee_etude]);
            $message = "Étudiant ajouté avec succès.";
        } elseif ($action === 'update' && $id) {
            // Mise à jour d'un étudiant existant
            $stmt = $pdo->prepare("UPDATE Apprenant SET matricule = ?, nom = ?, prenom = ?, email = ?, telephone = ?, 
                                 adresse = ?, genre = ?, filiere = ?, annee_etude = ? WHERE id = ?");
            $stmt->execute([$matricule, $nom, $prenom, $email, $telephone, $adresse, $genre, $filiere, $annee_etude, $id]);
            $message = "Étudiant mis à jour avec succès.";
        }

        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    }

    // Suppression d'un étudiant
    if (isset($_GET['delete_id'])) {
        $id = (int)$_GET['delete_id'];
        $stmt = $pdo->prepare("DELETE FROM Apprenant WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Étudiant supprimé avec succès.";
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    }
} catch (PDOException $e) {
    $message = "Erreur : " . $e->getMessage();
}

// Récupérer tous les étudiants groupés par filière
try {
    $stmt = $pdo->query("SELECT * FROM Apprenant ORDER BY filiere, nom, prenom");
    $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Grouper par filière
    $etudiants_par_filiere = [];
    foreach ($etudiants as $etudiant) {
        $filiere = $etudiant['filiere'];
        if (!isset($etudiants_par_filiere[$filiere])) {
            $etudiants_par_filiere[$filiere] = [];
        }
        $etudiants_par_filiere[$filiere][] = $etudiant;
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération des étudiants: " . $e->getMessage());
}

// Récupérer les données de l'étudiant à modifier
$etudiant_to_edit = null;
if (isset($_GET['edit_id'])) {
    $id_to_edit = (int)$_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM Apprenant WHERE id = ?");
    $stmt->execute([$id_to_edit]);
    $etudiant_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Étudiants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .filiere-header {
            background-color: #4e73df;
            color: white;
            padding: 10px 15px;
            margin-top: 20px;
            border-radius: 5px;
            font-weight: bold;
        }
        .student-card {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 15px;
            padding: 15px;
            transition: transform 0.2s;
        }
        .student-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .action-buttons .btn {
            margin: 0 5px;
        }
        #studentForm {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1 class="text-center mb-4">Gestion des Étudiants</h1>

        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>

        <!-- Bouton pour afficher le formulaire -->
        <div class="text-end mb-3">
            <button id="toggleFormButton" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> <?= $etudiant_to_edit ? "Modifier un étudiant" : "Ajouter un étudiant" ?>
            </button>
        </div>

        <!-- Formulaire pour ajout/modification -->
        <form id="studentForm" action="" method="POST" style="display: none;">
            <input type="hidden" name="action" value="<?= $etudiant_to_edit ? 'update' : 'add' ?>">
            <?php if ($etudiant_to_edit): ?>
                <input type="hidden" name="id" value="<?= $etudiant_to_edit['id'] ?>">
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="matricule" class="form-label">Matricule</label>
                        <input type="text" class="form-control" id="matricule" name="matricule" 
                               value="<?= $etudiant_to_edit ? htmlspecialchars($etudiant_to_edit['matricule']) : '' ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" 
                               value="<?= $etudiant_to_edit ? htmlspecialchars($etudiant_to_edit['nom']) : '' ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" 
                               value="<?= $etudiant_to_edit ? htmlspecialchars($etudiant_to_edit['prenom']) : '' ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= $etudiant_to_edit ? htmlspecialchars($etudiant_to_edit['email']) : '' ?>" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="telephone" name="telephone" 
                               value="<?= $etudiant_to_edit ? htmlspecialchars($etudiant_to_edit['telephone']) : '' ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="adresse" name="adresse" 
                               value="<?= $etudiant_to_edit ? htmlspecialchars($etudiant_to_edit['adresse']) : '' ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre</label>
                        <select class="form-select" id="genre" name="genre" required>
                            <option value="Homme" <?= $etudiant_to_edit && $etudiant_to_edit['genre'] === 'Homme' ? 'selected' : '' ?>>Homme</option>
                            <option value="Femme" <?= $etudiant_to_edit && $etudiant_to_edit['genre'] === 'Femme' ? 'selected' : '' ?>>Femme</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="filiere" class="form-label">Filière</label>
                        <select class="form-select" id="filiere" name="filiere" required>
                            <option value="Informatique" <?= $etudiant_to_edit && $etudiant_to_edit['filiere'] === 'Informatique' ? 'selected' : '' ?>>Informatique</option>
                            <option value="Gestion" <?= $etudiant_to_edit && $etudiant_to_edit['filiere'] === 'Gestion' ? 'selected' : '' ?>>Gestion</option>
                            <option value="Economie" <?= $etudiant_to_edit && $etudiant_to_edit['filiere'] === 'Economie' ? 'selected' : '' ?>>Économie</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="annee_etude" class="form-label">Année d'étude</label>
                        <select class="form-select" id="annee_etude" name="annee_etude" required>
                            <option value="ING1" <?= $etudiant_to_edit && $etudiant_to_edit['annee_etude'] === '1' ? 'selected' : '' ?>>ING1</option>
                            <option value="ING2" <?= $etudiant_to_edit && $etudiant_to_edit['annee_etude'] === '2' ? 'selected' : '' ?>>ING2</option>
                            <option value="ING3" <?= $etudiant_to_edit && $etudiant_to_edit['annee_etude'] === '3' ? 'selected' : '' ?>>ING3</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> <?= $etudiant_to_edit ? "Modifier" : "Ajouter" ?>
                </button>
                <button type="button" id="cancelForm" class="btn btn-secondary">Annuler</button>
            </div>
        </form>

        <!-- Affichage des étudiants par filière -->
        <?php if (!empty($etudiants_par_filiere)): ?>
            <?php foreach ($etudiants_par_filiere as $filiere => $etudiants): ?>
                <div class="filiere-header">
                    <h3><?= htmlspecialchars($filiere) ?></h3>
                </div>
                
                <div class="row">
                    <?php foreach ($etudiants as $etudiant): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="student-card">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5><?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></h5>
                                        <p class="mb-1"><strong>Matricule:</strong> <?= htmlspecialchars($etudiant['matricule']) ?></p>
                                        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($etudiant['email']) ?></p>
                                        <p class="mb-1"><strong>Téléphone:</strong> <?= htmlspecialchars($etudiant['telephone']) ?></p>
                                        <p class="mb-1"><strong>Année:</strong> <?= htmlspecialchars($etudiant['annee_etude']) ?></p>
                                        <p class="mb-1"><strong>Addresse</Address>:</strong> <?= htmlspecialchars($etudiant['adresse']) ?></p>

                                    </div>
                                    <div class="action-buttons d-flex flex-column">
                                        <a href="?edit_id=<?= $etudiant['id'] ?>" class="btn btn-sm btn-warning mb-2">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </a>
                                        <a href="?delete_id=<?= $etudiant['id'] ?>" class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Voulez-vous vraiment supprimer cet étudiant ?')">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">Aucun étudiant disponible</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gestion de l'affichage du formulaire
        const toggleFormButton = document.getElementById('toggleFormButton');
        const studentForm = document.getElementById('studentForm');
        const cancelForm = document.getElementById('cancelForm');

        toggleFormButton.addEventListener('click', () => {
            studentForm.style.display = studentForm.style.display === 'none' ? 'block' : 'none';
            toggleFormButton.textContent = studentForm.style.display === 'none' ? 
                '<i class="bi bi-plus-circle"></i> Ajouter un étudiant' : 
                'Masquer le formulaire';
        });

        cancelForm.addEventListener('click', () => {
            studentForm.style.display = 'none';
            toggleFormButton.textContent = '<i class="bi bi-plus-circle"></i> Ajouter un étudiant';
            window.location.href = window.location.pathname;
        });

        // Afficher le formulaire si on est en mode modification
        <?php if ($etudiant_to_edit): ?>
            studentForm.style.display = 'block';
            toggleFormButton.textContent = 'Masquer le formulaire';
        <?php endif; ?>
    </script>
</body>
</html>