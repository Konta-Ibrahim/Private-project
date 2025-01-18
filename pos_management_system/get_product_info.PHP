<?php
include 'config.php';

if (isset($_GET['barcode'])) {
    $barcode = $_GET['barcode'];
    $query = mysqli_query($conn, "SELECT * FROM `products` WHERE barcode = '$barcode'");
    if (mysqli_num_rows($query) > 0) {
        $product = mysqli_fetch_assoc($query);
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Produit non trouvé']);
    }
}
?>
