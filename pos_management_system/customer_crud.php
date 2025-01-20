<?php
// Inclure la connexion à la base de données
include 'config.php';

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Action par défaut : lire les clients
$action = $_GET['action'] ?? 'read';
$id = $_GET['id'] ?? null;
$message = "";

// Ajouter ou modifier un client
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $adresse = mysqli_real_escape_string($conn, $_POST['adresse']);
    $image = $_FILES['image']['name'] ?? null;

    if ($image) {
        $imagePath = 'uploads/' . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    if ($action === 'create') {
        $query = "INSERT INTO customer (name, contact, adresse, image) VALUES ('$name', '$contact', '$adresse', '$image')";
        mysqli_query($conn, $query) or die('Query failed');
        $message = "Client ajouté avec succès !";
    } elseif ($action === 'update' && $id) {
        if (!$image) {
            $query = "SELECT image FROM customer WHERE id = '$id'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $image = $row['image'];
        }
        $query = "UPDATE customer SET name = '$name', contact = '$contact', adresse = '$adresse', image = '$image' WHERE id = '$id'";
        mysqli_query($conn, $query) or die('Query failed');
        $message = "Client mis à jour avec succès !";
    }
    header("Location: customer_crud.php");
    exit;
}

// Supprimer un client
if ($action === 'delete' && $id) {
    $query = "SELECT image FROM customer WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $image = $row['image'];

    if ($image) unlink('uploads/' . $image);

    $query = "DELETE FROM customer WHERE id = '$id'";
    mysqli_query($conn, $query) or die('Query failed');
    $message = "Client supprimé avec succès !";
    header("Location: customer_crud.php");
    exit;
}

// Lire les clients
if ($action === 'read') {
    $query = "SELECT * FROM customer";
    $result = mysqli_query($conn, $query) or die('Query failed');
    $customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Obtenir les données pour modification
if ($action === 'update' && $id) {
    $query = "SELECT * FROM customer WHERE id = '$id'";
    $result = mysqli_query($conn, $query) or die('Query failed');
    $customer = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include "header.php"?>


    <h1>Gestion des Clients</h1>

    <?php if (!empty($message)): ?>
        <p style="color: green;"><?= $message ?></p>
    <?php endif; ?>

    <!-- Formulaire pour Ajouter/Modifier un client -->
    <?php if ($action === 'create' || $action === 'update'): ?>
        <h2><?= $action === 'create' ? 'Ajouter' : 'Modifier' ?> un Client</h2>
        <form action="customer_crud.php?action=<?= $action ?><?= $id ? '&id=' . $id : '' ?>" method="POST" enctype="multipart/form-data">
            <label>Nom : <input type="text" name="name" value="<?= $customer['name'] ?? '' ?>" required></label><br>
            <label>Contact : <input type="text" name="contact" value="<?= $customer['contact'] ?? '' ?>" required></label><br>
            <label>Adresse : <textarea name="adresse"><?= $customer['adresse'] ?? '' ?></textarea></label><br>
            <label>Image : <input type="file" name="image"></label><br>
            <?php if (!empty($customer['image'])): ?>
                <img src="uploads/<?= $customer['image'] ?>" alt="Image" width="50"><br>
            <?php endif; ?>
            <button type="submit"><?= $action === 'create' ? 'Ajouter' : 'Modifier' ?></button>
        </form>
        <a href="customer_crud.php">Annuler</a>
    <?php else: ?>
        <!-- Liste des clients -->
        <h2>Liste des Clients</h2>
        <a href="customer_crud.php?action=create">Ajouter un Client</a>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Contact</th>
                    <th>Adresse</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= $customer['id'] ?></td>
                        <td><?= htmlspecialchars($customer['name']) ?></td>
                        <td><?= htmlspecialchars($customer['contact']) ?></td>
                        <td><?= htmlspecialchars($customer['adresse']) ?></td>
                        <td>
                            <?php if (!empty($customer['image'])): ?>
                                <img src="uploads/<?= $customer['image'] ?>" alt="Image" width="50">
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="customer_crud.php?action=update&id=<?= $customer['id'] ?>">Modifier</a>
                            <a href="customer_crud.php?action=delete&id=<?= $customer['id'] ?>" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
