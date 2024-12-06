<?php
$servername = "localhost";  // Assurez-vous d'utiliser les bonnes informations
$username = "root";         // Nom d'utilisateur MySQL
$password = "Konta009%";             // Mot de passe MySQL (s'il y en a un)
$dbname = "db_pos";         // Nom de la base de données

// Créez la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}
?>
