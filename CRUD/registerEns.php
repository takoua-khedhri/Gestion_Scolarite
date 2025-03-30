<?php
session_start();
require "db.php";
$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validation côté serveur
    if (empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM educateur WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier le mot de passe
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = 'enseignant'; // Pour différencier les types d'utilisateurs
                header("Location: DashboardEnseignat.php");
                exit;
            } else {
                $error = "Mot de passe invalide.";
            }
        } else {
            $error = "Email non trouvé.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Enseignant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        .login-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
            max-width: 500px;
            margin: 0 auto;
        }
        .card-header {
            background-color: #4e73df;
            color: white;
            text-align: center;
            padding: 1.5rem;
            border-bottom: none;
        }
        .card-body {
            padding: 2rem;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }
        .btn-login {
            background-color: #4e73df;
            border: none;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        .btn-login:hover {
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
        .forgot-password {
            text-align: right;
            margin-top: -10px;
            margin-bottom: 20px;
        }
        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }
        .divider-text {
            padding: 0 10px;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card card">
            <div class="card-header">
                <h3><i class="bi bi-person-badge"></i> Connexion Enseignant</h3>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4">
                        <label for="email" class="form-label">Adresse Email</label>
                        <div class="input-icon">
                            <input type="email" id="email" name="email" class="form-control" 
                                   placeholder="votre@email.com" required>
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="input-icon">
                            <input type="password" id="password" name="password" class="form-control" 
                                   placeholder="Votre mot de passe" required>
                            <i class="bi bi-lock"></i>
                        </div>
                        
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-login btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Se connecter
                        </button>
                    </div>

                    <div class="divider">
                        <span class="divider-text">OU</span>
                    </div>

                    <div class="text-center mt-3">
                        <p class="mb-0">Pas encore de compte ? <a href="registerEns.php" class="text-decoration-none">S'inscrire</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>