<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Utilisateur - Tableau de Bord</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>Bienvenue, Utilisateur <?php echo $_SESSION['username']; ?>!</h1>
    <a href="../others/logout.php">Déconnexion</a>
</body>
</html>
