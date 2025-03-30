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
        $stmt = $pdo->prepare("SELECT * FROM apprenant WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier le mot de passe
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = 'etudiant'; // Pour identifier le type d'utilisateur
                header("Location: DashboardEtu.php");
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
    <title>Connexion Étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ed 100%);
        }
        .login-container {
            max-width: 450px;
            margin: 0 auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: #2c3e50;
            font-weight: 600;
        }
        .login-header p {
            color: #7f8c8d;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #dfe6e9;
        }
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }
        .btn-login {
            background-color: #4e73df;
            border: none;
            padding: 12px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            width: 100%;
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
            color: #7f8c8d;
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
            border-bottom: 1px solid #dfe6e9;
        }
        .divider-text {
            padding: 0 10px;
            color: #7f8c8d;
            font-size: 14px;
        }
        .alert-danger {
            border-left: 4px solid #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <h2><i class="bi bi-person-circle"></i> Connexion Étudiant</h2>
                <p>Accédez à votre espace personnel</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
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
                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right"></i> Se connecter
                    </button>
                </div>

                <div class="divider">
                    <span class="divider-text">OU</span>
                </div>

                <div class="text-center mt-3">
                    <p class="mb-0">Pas encore de compte ? <a href="registerEtu.php" class="text-decoration-none">S'inscrire</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>