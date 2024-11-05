<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Tableau de Bord</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <h1>Bienvenue, Admin <?php echo $_SESSION['username']; ?>!</h1>
    <a href="manage_superusers.php">Gérer les utilisateurs et super-utilisateurs</a>
    <a href="../others/logout.php">Déconnexion</a>
</body>
</html>
