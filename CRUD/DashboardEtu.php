<?php
session_start();

$id_etudiant = $_SESSION['user_id']; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Étudiant</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
        <div class="position-sticky pt-3">
          <h4 class="text-center mb-4">Dashboard Étudiant</h4>
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link" href="listeNoteEtu.php?id=<?php echo $id_etudiant; ?>">
                <i class="bi bi-book"></i> Matières et Notes
              </a>
            </li>
           
            <li class="nav-item">
              <a class="nav-link" href="#">
                <i class="bi bi-calendar-x"></i> Absences
              </a>
            </li>
            <li class="nav-item mt-3">
              <a class="nav-link text-danger" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> Logout
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Content -->
      <main class="col-md-9 ms-sm-auto col-lg-10 content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        </div>
        <!-- Vous pouvez ajouter ici d'autres contenus ou widgets -->
      </main>
    </div>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Optionnel : inclusion des icônes Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
