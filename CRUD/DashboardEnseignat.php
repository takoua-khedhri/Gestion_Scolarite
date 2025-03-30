<?php
session_start();
require 'db.php';



// Récupérer les étudiants (nom, prénom, numéro)
$stmt = $pdo->prepare("SELECT id, nom, prenom, telephone FROM apprenant");
$stmt->execute();
$etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
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

    <div class="sidebar">
        <h4 class="p-3">Dashboard Enseignant</h4>
        <a href="matiere.php?id=<?php echo $id_enseignant;?>">Ma Matière enseignee</a>
        <a href="GestionNoteEns.php">Gestion des notes</a>
        <a href="logout.php" class="text-danger">Logout</a>
    </div>

    <div class="content">
        <h2>Liste des étudiants</h2>

        <!-- Barre de recherche -->
        <input type="text" id="search" class="form-control mb-3" placeholder="Rechercher par nom ou numéro...">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Numéro</th>
                </tr>
            </thead>
            <tbody id="etudiants-list">
                <?php foreach ($etudiants as $etudiant): ?>
                    <tr>
                        <td class="etudiant-nom"><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                        <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($etudiant['telephone']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Fonction de filtrage de la liste des étudiants
        document.getElementById('search').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#etudiants-list tr');
            rows.forEach(row => {
                let nom = row.querySelector('.etudiant-nom').textContent.toLowerCase();
                let numero = row.querySelectorAll('td')[2].textContent.toLowerCase();
                if (nom.includes(filter) || numero.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

</body>
</html>
