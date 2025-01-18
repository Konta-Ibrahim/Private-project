<?php
include 'db.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $barcode = $_POST['barcode'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $query = "INSERT INTO products (name, barcode, price, stock) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdi", $name, $barcode, $price, $stock);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Produit ajouté avec succès"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout du produit"]);
    }
}
?>
