<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $code = $data['code'];

    // Check if the product exists in the database
    $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE barcode = '$code'") or die(json_encode(['error' => 'Query failed']));
    if (mysqli_num_rows($product_query) > 0) {
        $product = mysqli_fetch_assoc($product_query);
        echo json_encode([
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'stock' => $product['stock'],
            'barcode' => $product['barcode']
        ]);
    } else {
        echo json_encode(['error' => 'Product not found!']);
    }
}
?>
