<?php
session_start();
require 'db.php';

$message="";


//Recupertaion
try {
    $stmt = $pdo->query("SELECT * FROM apprenant");
    $etudiant = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching etudiants: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Liste des etudiants</title>
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
    <div class="container mt-5">
        <h2 class="mb-4">Liste des etudiants</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Email</th>
                    <th>Telephone</th>
                    <th>Adresse</th>
                    <th>Matricule</th>
                    <th>Genre</th>
                    <th>Nationalité</th>
                    <th>Filiére</th>
                    <th>Année d'étude</th>
                    <th>Actions</th>
                </tr>

              

            </thead>
            <tbody>
                <?php foreach ($etudiant as $etudiants): ?>
                    <tr>
                        <td><?=htmlspecialchars($etudiants['nom']) ?></td>
                        <td><?=htmlspecialchars($etudiants['prenom']) ?></td>
                        <td><?=htmlspecialchars($etudiants['email']) ?></td>
                        <td><?=htmlspecialchars($etudiants['telephone']) ?></td>
                        <td><?=htmlspecialchars($etudiants['adresse']) ?></td>
                        <td><?=htmlspecialchars($etudiants['matricule']) ?></td>

                        <td><?=htmlspecialchars($etudiants['genre']) ?></td>
                        <td><?=htmlspecialchars($etudiants['nationalite']) ?></td>
                        <td><?=htmlspecialchars($etudiants['filiere']) ?></td>
                        <td><?=htmlspecialchars($etudiants['annee_etude']) ?></td>
                        
                    </tr>
                    <?php endforeach; ?>
</tbody>
</table>
</div>
</body>
</html>

