<?php
session_start();

// Vérifie si l'utilisateur est déjà connecté
if (isset($_SESSION["username"])) {
    echo "Vous êtes déjà connecté en tant que " . $_SESSION['username'];
    header('location:dashboardAdm.php');
    exit;
}

// Initialise les variables $username et $password
$username = "";
$password = "";

// Vérifie si le formulaire de connexion a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
}

if ($username === 'admin' && $password === 'admin') {
    // Si les identifiants sont corrects, enregistre l'utilisateur dans la session
    $_SESSION['username'] = $username;
    echo "Bienvenue " . $_SESSION['username'];
    header('location:dashboardAdm.php');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Formulaire centré à 100% -->
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card p-4" style="width: 100%; max-width: 400px;">
            <h2 class="text-center mb-4">Se connecter</h2>

            <!-- Formulaire de connexion -->
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" id="username" class="form-control" name="username" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" id="password" class="form-control" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>

            <!-- Message d'erreur en cas d'échec de connexion -->
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($username !== 'admin' || $password !== 'admin')): ?>
                <div class="alert alert-danger mt-3" role="alert">
                    Nom d'utilisateur ou mot de passe incorrect
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
