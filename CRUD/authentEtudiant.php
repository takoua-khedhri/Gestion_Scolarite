<?php
session_start();
require "db.php";

$errors = [];

// [Votre code PHP existant pour le traitement du formulaire...]
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .registration-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .form-header {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }
        .form-header h2 {
            font-weight: 600;
        }
        .form-section {
            margin-bottom: 25px;
            padding: 20px;
            background-color: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #4e73df;
        }
        .form-section h5 {
            color: #4e73df;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .form-label {
            font-weight: 500;
            color: #495057;
        }
        .form-control {
            border-radius: 5px;
            padding: 10px 15px;
            border: 1px solid #ced4da;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        .btn-register {
            background-color: #4e73df;
            border: none;
            padding: 12px 25px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        .btn-register:hover {
            background-color: #3a5bbf;
            transform: translateY(-2px);
        }
        .input-icon {
            position: relative;
        }
        .input-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .alert-danger {
            border-left: 4px solid #dc3545;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <div class="form-header">
            <h2><i class="bi bi-person-plus"></i> Inscription Étudiant</h2>
            <p class="text-muted">Remplissez le formulaire pour créer votre compte</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Erreurs à corriger</h5>
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <!-- Section Informations Personnelles -->
            <div class="form-section">
                <h5><i class="bi bi-person-vcard"></i> Informations Personnelles</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label required-field">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="prenom" class="form-label required-field">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Votre prénom" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date_naissance" class="form-label required-field">Date de naissance</label>
                        <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="genre" class="form-label required-field">Genre</label>
                        <select class="form-control" id="genre" name="genre" required>
                            <option value="Homme">Homme</option>
                            <option value="Femme">Femme</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="nationalite" class="form-label required-field">Nationalité</label>
                    <input type="text" class="form-control" id="nationalite" name="nationalite" placeholder="Votre nationalité" required>
                </div>
            </div>

            <!-- Section Coordonnées -->
            <div class="form-section">
                <h5><i class="bi bi-geo-alt"></i> Coordonnées</h5>
                <div class="mb-3">
                    <label for="email" class="form-label required-field">Email</label>
                    <div class="input-icon">
                        <input type="email" class="form-control" id="email" name="email" placeholder="exemple@domain.com" required>
                        <i class="bi bi-envelope"></i>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="telephone" class="form-label required-field">Téléphone</label>
                    <div class="input-icon">
                        <input type="text" class="form-control" id="telephone" name="telephone" required>
                        <i class="bi bi-phone"></i>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="adresse" class="form-label required-field">Adresse</label>
                    <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Votre adresse complète" required>
                </div>
                <div class="mb-3">
                    <label for="code_postal" class="form-label required-field">Code Postal</label>
                    <input type="text" class="form-control" id="code_postal" name="code_postal" placeholder="Code postal" required>
                </div>
            </div>

            <!-- Section Scolarité -->
            <div class="form-section">
                <h5><i class="bi bi-book"></i> Informations Scolaires</h5>
                <div class="mb-3">
                    <label for="filiere" class="form-label required-field">Filière</label>
                    <select class="form-control" id="filiere" name="filiere" required>
                        <option value="">Sélectionnez une filière</option>
                        <option value="Informatique">Informatique</option>
                        <option value="Economie">Economie</option>
                        <option value="Gestion">Gestion</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="annee_etude" class="form-label required-field">Niveau d'étude</label>
                    <select class="form-control" id="annee_etude" name="annee_etude" required>
                        <option value="">Sélectionnez une année</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="matricule" class="form-label required-field">Matricule</label>
                    <div class="input-icon">
                        <input type="text" class="form-control" id="matricule" name="matricule" placeholder="Votre numéro de matricule" required>
                        <i class="bi bi-123"></i>
                    </div>
                </div>
            </div>

            <!-- Section Sécurité -->
            <div class="form-section">
                <h5><i class="bi bi-shield-lock"></i> Sécurité</h5>
                <div class="mb-3">
                    <label for="password" class="form-label required-field">Mot de passe</label>
                    <div class="input-icon">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Créez un mot de passe sécurisé" required>
                        <i class="bi bi-lock"></i>
                    </div>
                    <small class="text-muted">Minimum 8 caractères avec chiffres et lettres</small>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-register btn-lg">
                    <i class="bi bi-person-plus"></i> S'inscrire
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <p>Vous avez déjà un compte ? <a href="registerEtu.php" class="text-decoration-none">Connectez-vous ici</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>