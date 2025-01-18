<?php
include('../includes/config.php');
$barcode = $_GET['barcode'];
$result = $conn->query("SELECT * FROM products WHERE barcode = '$barcode'");
if ($result->num_rows > 0) {
    echo json_encode(['success' => true, 'product' => $result->fetch_assoc()]);
} else {
    echo json_encode(['success' => false]);
}
?>
