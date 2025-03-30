<?php
session_start();
require 'db.php';

// Fonction pour valider les données
function validateInput($data) {
    return htmlspecialchars(trim($data));
}

// Récupérer la liste des matières depuis la base de données
try {
    // Récupérer toutes les matières avec leurs filières
    $stmt_matieres = $pdo->query("SELECT id, nom_matiere, filiere FROM G_matiere ORDER BY nom_matiere");
    $matieres = $stmt_matieres->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer les filières DISTINCTES et les trier
    $stmt_filieres = $pdo->query("SELECT DISTINCT filiere FROM G_matiere ORDER BY filiere");
    $filieres_distinctes = $stmt_filieres->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données: " . $e->getMessage());
}


// Traitement du formulaire
try {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

        // Récupération des données POST
        $nom = validateInput($_POST['nom']);
        $prenom = validateInput($_POST['prenom']);
        $date_naissance = validateInput($_POST['date_naissance']);
        $email = validateInput($_POST['email']);
        $telephone = validateInput($_POST['telephone']);
        $adresse = validateInput($_POST['adresse']);
        $ville = validateInput($_POST['ville']);
        $code_postal = validateInput($_POST['code_postal']);
        $genre = validateInput($_POST['genre']);
        $nationalite = validateInput($_POST['nationalite']);
        $matiere_enseignee_id = validateInput($_POST['matiere_enseignee']);
        $departement = validateInput($_POST['departement']);
        
        // Récupérer le nom de la matière à partir de l'ID
        $matiere_nom = '';
        foreach ($matieres as $matiere) {
            if ($matiere['id'] == $matiere_enseignee_id) {
                $matiere_nom = $matiere['nom_matiere'];
                break;
            }
        }
        
        // Gestion du mot de passe
        $password = $enseignant_to_edit['password'] ?? ''; // Conserver l'ancien mot de passe par défaut
        if (!empty($_POST['password'])) {
            $password = password_hash(validateInput($_POST['password']), PASSWORD_DEFAULT);
        }

        if ($action === 'add') {
            // Ajout d'un nouvel enseignant
            $stmt = $pdo->prepare("INSERT INTO Educateur (nom, prenom, date_naissance, email, telephone, adresse, ville, code_postal, genre, nationalite, matiere_enseignee, password, departement) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $date_naissance, $email, $telephone, $adresse, $ville, $code_postal, $genre, $nationalite, $matiere_nom, $password, $departement]);
            $message = "Enseignant ajouté avec succès.";
        } elseif ($action === 'update' && $id) {
            // Mise à jour d'un enseignant existant
            $stmt = $pdo->prepare("UPDATE Educateur SET nom = ?, prenom = ?, date_naissance = ?, email = ?, telephone = ?, 
                                 adresse = ?, ville = ?, code_postal = ?, genre = ?, nationalite = ?, 
                                 matiere_enseignee = ?, password = ?, departement = ? WHERE id = ?");
            $stmt->execute([$nom, $prenom, $date_naissance, $email, $telephone, $adresse, $ville, $code_postal, $genre, $nationalite, $matiere_nom, $password, $departement, $id]);
            $message = "Enseignant mis à jour avec succès.";
        }

        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    }

    // Suppression d'un enseignant
    if (isset($_GET['delete_id'])) {
        $id = (int)$_GET['delete_id'];
        $stmt = $pdo->prepare("DELETE FROM Educateur WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Enseignant supprimé avec succès.";
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    }
} catch (PDOException $e) {
    $message = "Erreur : " . $e->getMessage();
}

// Récupérer tous les enseignants groupés par filière (département)
try {
    $stmt = $pdo->query("SELECT * FROM Educateur ORDER BY departement, nom, prenom");
    $enseignants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Grouper par filière
    $enseignants_par_filiere = [];
    foreach ($enseignants as $enseignant) {
        $filiere = $enseignant['departement'];
        if (!isset($enseignants_par_filiere[$filiere])) {
            $enseignants_par_filiere[$filiere] = [];
        }
        $enseignants_par_filiere[$filiere][] = $enseignant;
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération des enseignants: " . $e->getMessage());
}

// Récupérer les données de l'enseignant à modifier
$enseignant_to_edit = null;
if (isset($_GET['edit_id'])) {
    $id_to_edit = (int)$_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM Educateur WHERE id = ?");
    $stmt->execute([$id_to_edit]);
    $enseignant_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Trouver l'ID de la matière enseignée
    if ($enseignant_to_edit) {
        foreach ($matieres as $matiere) {
            if ($matiere['nom_matiere'] === $enseignant_to_edit['matiere_enseignee']) {
                $enseignant_to_edit['matiere_id'] = $matiere['id'];
                break;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Enseignants</title>
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
        .teacher-card {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 15px;
            padding: 15px;
            transition: transform 0.2s;
        }
        .teacher-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .action-buttons .btn {
            margin: 0 5px;
        }
        #teacherForm {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 30px;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1 class="text-center mb-4">Gestion des Enseignants</h1>

        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>

        <!-- Bouton pour afficher le formulaire -->
        <div class="text-end mb-3">
            <button id="toggleFormButton" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> <?= $enseignant_to_edit ? "Modifier un enseignant" : "Ajouter un enseignant" ?>
            </button>
        </div>

        <!-- Formulaire pour ajout/modification -->
        <form id="teacherForm" action="" method="POST" style="display: none;">
            <input type="hidden" name="action" value="<?= $enseignant_to_edit ? 'update' : 'add' ?>">
            <?php if ($enseignant_to_edit): ?>
                <input type="hidden" name="id" value="<?= $enseignant_to_edit['id'] ?>">
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nom" class="form-label required-field">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" 
                               value="<?= $enseignant_to_edit ? htmlspecialchars($enseignant_to_edit['nom']) : '' ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="prenom" class="form-label required-field">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" 
                               value="<?= $enseignant_to_edit ? htmlspecialchars($enseignant_to_edit['prenom']) : '' ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="date_naissance" class="form-label required-field">Date de naissance</label>
                        <input type="date" class="form-control" id="date_naissance" name="date_naissance" 
                               value="<?= $enseignant_to_edit ? htmlspecialchars($enseignant_to_edit['date_naissance']) : '' ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label required-field">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= $enseignant_to_edit ? htmlspecialchars($enseignant_to_edit['email']) : '' ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telephone" class="form-label required-field">Téléphone</label>
                        <input type="text" class="form-control" id="telephone" name="telephone" 
                               value="<?= $enseignant_to_edit ? htmlspecialchars($enseignant_to_edit['telephone']) : '' ?>" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="adresse" name="adresse" 
                               value="<?= $enseignant_to_edit ? htmlspecialchars($enseignant_to_edit['adresse']) : '' ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="ville" class="form-label">Ville</label>
                        <input type="text" class="form-control" id="ville" name="ville" 
                               value="<?= $enseignant_to_edit ? htmlspecialchars($enseignant_to_edit['ville']) : '' ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="code_postal" class="form-label">Code postal</label>
                        <input type="text" class="form-control" id="code_postal" name="code_postal" 
                               value="<?= $enseignant_to_edit ? htmlspecialchars($enseignant_to_edit['code_postal']) : '' ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="genre" class="form-label required-field">Genre</label>
                        <select class="form-select" id="genre" name="genre" required>
                            <option value="Homme" <?= $enseignant_to_edit && $enseignant_to_edit['genre'] === 'Homme' ? 'selected' : '' ?>>Homme</option>
                            <option value="Femme" <?= $enseignant_to_edit && $enseignant_to_edit['genre'] === 'Femme' ? 'selected' : '' ?>>Femme</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nationalite" class="form-label">Nationalité</label>
                        <input type="text" class="form-control" id="nationalite" name="nationalite" 
                               value="<?= $enseignant_to_edit ? htmlspecialchars($enseignant_to_edit['nationalite']) : '' ?>">
                    </div>
                    

                    
                    <div class="mb-3">
    <label for="matiere_enseignee" class="form-label required-field">Matière enseignée</label>
    <select class="form-select" id="matiere_enseignee" name="matiere_enseignee" required>
        <option value="">Sélectionnez une matière</option>
        <?php foreach ($matieres as $matiere): ?>
            <option value="<?= $matiere['id'] ?>" 
                <?= isset($enseignant_to_edit['matiere_id']) && $enseignant_to_edit['matiere_id'] == $matiere['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($matiere['nom_matiere']) ?> (<?= htmlspecialchars($matiere['filiere']) ?>)
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label for="departement" class="form-label required-field">Département/Filière</label>
    <select class="form-select" id="departement" name="departement" required>
        <option value="">Sélectionnez une filière</option>
        <?php foreach ($filieres_distinctes as $filiere): ?>
            <option value="<?= htmlspecialchars($filiere['filiere']) ?>" 
                <?= isset($enseignant_to_edit['departement']) && $enseignant_to_edit['departement'] == $filiere['filiere'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($filiere['filiere']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
                </div>
            </div>
            
            <div class="text-end">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> <?= $enseignant_to_edit ? "Modifier" : "Ajouter" ?>
                </button>
                <button type="button" id="cancelForm" class="btn btn-secondary">Annuler</button>
            </div>
        </form>

        <!-- Affichage des enseignants par filière -->
        <?php if (!empty($enseignants_par_filiere)): ?>
            <?php foreach ($enseignants_par_filiere as $filiere => $enseignants): ?>
                <div class="filiere-header">
                    <h3><?= htmlspecialchars($filiere) ?></h3>
                </div>
                
                <div class="row">
                    <?php foreach ($enseignants as $enseignant): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="teacher-card">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5><?= htmlspecialchars($enseignant['prenom'] . ' ' . $enseignant['nom']) ?></h5>
                                        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($enseignant['email']) ?></p>
                                        <p class="mb-1"><strong>Téléphone:</strong> <?= htmlspecialchars($enseignant['telephone']) ?></p>
                                        <p class="mb-1"><strong>Matière:</strong> <?= htmlspecialchars($enseignant['matiere_enseignee']) ?></p>
                                        <p class="mb-1"><strong>Date naissance:</strong> <?= htmlspecialchars($enseignant['date_naissance']) ?></p>
                                    </div>
                                    <div class="action-buttons d-flex flex-column">
                                        <a href="?edit_id=<?= $enseignant['id'] ?>" class="btn btn-sm btn-warning mb-2">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </a>
                                        <a href="?delete_id=<?= $enseignant['id'] ?>" class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Voulez-vous vraiment supprimer cet enseignant ?')">
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
            <div class="alert alert-info">Aucun enseignant disponible</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gestion de l'affichage du formulaire
        const toggleFormButton = document.getElementById('toggleFormButton');
        const teacherForm = document.getElementById('teacherForm');
        const cancelForm = document.getElementById('cancelForm');

        toggleFormButton.addEventListener('click', () => {
            teacherForm.style.display = teacherForm.style.display === 'none' ? 'block' : 'none';
            toggleFormButton.textContent = teacherForm.style.display === 'none' ? 
                '<i class="bi bi-plus-circle"></i> Ajouter un enseignant' : 
                'Masquer le formulaire';
        });

        cancelForm.addEventListener('click', () => {
            teacherForm.style.display = 'none';
            toggleFormButton.textContent = '<i class="bi bi-plus-circle"></i> Ajouter un enseignant';
            window.location.href = window.location.pathname;
        });

        // Afficher le formulaire si on est en mode modification
        <?php if ($enseignant_to_edit): ?>
            teacherForm.style.display = 'block';
            toggleFormButton.textContent = 'Masquer le formulaire';
        <?php endif; ?>
    </script>
</body>
</html>