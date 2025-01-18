<?php
include 'config.php';
header('Content-Type: application/json');

// Récupérer les données JSON
$input = json_decode(file_get_contents('php://input'), true);
if (isset($input['barcode'])) {
    $barcode = $input['barcode'];

    // Vérifier si le produit existe déjà
    $check = mysqli_query($conn, "SELECT * FROM `products` WHERE barcode = '$barcode'");
    if (mysqli_num_rows($check) > 0) {
        echo json_encode(['success' => false, 'message' => 'Produit déjà existant.']);
    } else {
        $name = "Produit $barcode";
        $price = 10; // Prix par défaut
        $stock = 1;  // Stock par défaut

        $insert = mysqli_query($conn, "INSERT INTO `products` (name, price, stock, barcode) VALUES ('$name', '$price', '$stock', '$barcode')");
        if ($insert) {
            echo json_encode(['success' => true, 'message' => 'Produit ajouté.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur d\'insertion.']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Code-barres manquant.']);
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
