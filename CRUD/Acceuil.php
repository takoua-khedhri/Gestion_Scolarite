<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisissez votre rôle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-image: url('images/education.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            font-family: 'Arial', sans-serif;
            color: white;
        }
        .container {
            margin-top: 50px;
            text-align: center;
            z-index: 10;
        }
        .btn-custom {
            font-size: 1.2rem;
            padding: 15px 30px;
            width: 250px;
            margin: 15px 0;
            border-radius: 50px;
            transition: transform 0.3s ease;
        }
        .btn-custom:hover {
            transform: scale(1.1);
        }
        .icon {
            margin-right: 10px;
        }
        .title {
            font-size: 3rem;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }
        .text-description {
            font-size: 1.3rem;
            margin-bottom: 40px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7);
        }
        .btn-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 10;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="container">
        <h1 class="title">Bienvenue dans votre espace</h1>
        <p class="text-description">Veuillez choisir votre rôle pour accéder à votre espace.</p>
    </div>

    <div class="btn-container">
        <a href="loginAdm.php" class="btn btn-primary btn-custom">
            <i class="bi bi-person-lock icon"></i>Administrateur
        </a>
        <a href="authenEnseigant.php" class="btn btn-success btn-custom">
            <i class="bi bi-person-bounding-box icon"></i>Enseignant
        </a>
        <a href="authentEtudiant.php" class="btn btn-warning btn-custom">
            <i class="bi bi-person-fill icon"></i>Etudiant
        </a>
    </div>

</body>
</html>
