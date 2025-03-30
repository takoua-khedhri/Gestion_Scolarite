<?php
// Connexion à la base de données
$servername = "localhost";
$port = '3307'; // Port personnalisé (au lieu de 3306 par défaut)
$username = "root"; 
$password = ""; // Mot de passe vide si vous n'en avez pas configuré
$dbname = "examen"; // Nom de votre base de données

try {
    // Ajout du port dans le DSN (Data Source Name)
    $dsn = "mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    
    // Configurer PDO pour afficher les erreurs SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Optionnel : Vérifier que la connexion fonctionne
    // $pdo->query("SELECT 1"); // Test simple
    // echo "Connexion réussie !";
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage()); // Arrête le script en cas d'échec
}
?>