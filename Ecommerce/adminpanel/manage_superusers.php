<?php
session_start();
include '../others/db.php';

// Vérification si l'utilisateur connecté est bien un admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Création d'un nouvel utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$username, $password, $role]);
        $success = "Utilisateur créé avec succès !";
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

// Récupération de la liste des utilisateurs existants
$users = $pdo->query("SELECT id, username, role FROM users")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Utilisateurs - Admin</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <h2>Gestion des Utilisateurs</h2>

    <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <!-- Liste des utilisateurs -->
    <h3>Liste des Utilisateurs</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom d'utilisateur</th>
            <th>Rôle</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['role']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Formulaire de création d'utilisateur -->
    <h3>Créer un Nouvel Utilisateur</h3>
    <form action="manage_superusers.php" method="POST">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <select name="role" required>
            <option value="user">Utilisateur</option>
            <option value="superuser">Super-utilisateur</option>
        </select>
        <button type="submit" name="create_user">Créer l'utilisateur</button>
    </form>

    <a href="admin_dashboard.php">Retour au tableau de bord</a>
    <a href="../others/logout.php">Déconnexion</a>
</body>
</html>
