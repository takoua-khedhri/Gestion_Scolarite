<?php
session_start();
require 'db.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrateur</title>
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
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4>Dashboard Administrateur</h4>
        <a href="GestionMatiereAdm.php">
            <i class="bi bi-folder"></i> Gestion des Matière
        </a>
        <a href="ConsultNoteAdm.php">
            <i class="bi bi-person"></i> Consulter les notes 
        </a>
        <a href="GestionEtuAdm.php">
            <i class="bi bi-person-bounding-box"></i> Gestion des etudiants
        </a>
        <a href="GestionEnsAdm.php">
            <i class="bi bi-person-bounding-box"></i> Gestion des Enseignants
        </a>
        <a href="logout.php" class="btn-logout">
            <i class="bi bi-box-arrow-right"></i> logout
        </a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Bienvenue dans le Dashboard Administrateur</h2>
        <p>Voici les outils pour gérer les matières, les étudiants et les enseignants.</p>
    </div>

</body>
</html>
