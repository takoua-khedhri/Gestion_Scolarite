<?php
session_start();
require "db.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // [Votre code de traitement du formulaire existant...]
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Enseignant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .registration-card {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background-color: #4e73df;
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 1.5rem;
        }
        .form-control {
            border-radius: 5px;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }
        .btn-primary {
            background-color: #4e73df;
            border: none;
            padding: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #3a5bbf;
            transform: translateY(-2px);
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 5px;
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
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card registration-card">
                    <div class="card-header text-center">
                        <h2><i class="bi bi-person-plus"></i> Inscription Enseignant</h2>
                    </div>
                    <div class="card-body p-4">
                        <!-- Affichage des erreurs -->
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Erreurs</h5>
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nom" class="form-label">Nom</label>
                                        <div class="input-icon">
                                            <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom" required>
                                            <i class="bi bi-person"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="prenom" class="form-label">Prénom</label>
                                        <div class="input-icon">
                                            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Votre prénom" required>
                                            <i class="bi bi-person"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="date_naissance" class="form-label">Date de naissance</label>
                                        <div class="input-icon">
                                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                                            <i class="bi bi-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="genre" class="form-label">Genre</label>
                                        <select class="form-control" id="genre" name="genre" required>
                                            <option value="Homme">Homme</option>
                                            <option value="Femme">Femme</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-icon">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="exemple@domain.com" required>
                                    <i class="bi bi-envelope"></i>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <div class="input-icon">
                                    <input type="text" class="form-control" id="telephone" name="telephone" placeholder="+212 6 12 34 56 78">
                                    <i class="bi bi-telephone"></i>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse</label>
                                <div class="input-icon">
                                    <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Votre adresse complète">
                                    <i class="bi bi-house"></i>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ville" class="form-label">Ville</label>
                                        <div class="input-icon">
                                            <input type="text" class="form-control" id="ville" name="ville" placeholder="Votre ville">
                                            <i class="bi bi-geo-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code_postal" class="form-label">Code Postal</label>
                                        <input type="text" class="form-control" id="code_postal" name="code_postal" placeholder="Code postal">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="nationalite" class="form-label">Nationalité</label>
                                <input type="text" class="form-control" id="nationalite" name="nationalite" placeholder="Votre nationalité">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="matiere_enseignee" class="form-label">Matière enseignée</label>
                                        <select class="form-control" id="matiere_enseignee" name="matiere_enseignee" required>
                                            <option value="">Sélectionnez une matière</option>
                                            <option value="Système d'exploitation">Système d'exploitation</option>
                                            <option value="Base de données">Base de données</option>
                                            <option value="Algorithmique">Algorithmique</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="departement" class="form-label">Département</label>
                                        <select class="form-control" id="departement" name="departement" required>
                                            <option value="">Sélectionnez un département</option>
                                            <option value="Informatique">Informatique</option>
                                            <option value="Gestion">Gestion</option>
                                            <option value="Economie">Economie</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <div class="input-icon">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Créez un mot de passe sécurisé" required>
                                    <i class="bi bi-lock"></i>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-person-plus"></i> S'inscrire
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <p class="mb-0">Vous avez déjà un compte ? <a href="registerEns.php" class="text-decoration-none">Connectez-vous ici</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>