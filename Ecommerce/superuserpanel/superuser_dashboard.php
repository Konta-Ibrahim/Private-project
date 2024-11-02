<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'superuser') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Super-utilisateur - Tableau de Bord</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>Bienvenue, Super-utilisateur <?php echo $_SESSION['username']; ?>!</h1>
    <a href="manage_users.php">Gérer les Utilisateurs</a>
    <a href="../others/logout.php">Déconnexion</a>
</body>
</html>
