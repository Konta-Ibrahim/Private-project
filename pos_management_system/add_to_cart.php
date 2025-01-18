<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

if (isset($_POST['barcode'])) {
    $barcode = $_POST['barcode'];

    // Fetch product details by barcode
    $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE barcode = '$barcode'") or die('Query failed');

    if (mysqli_num_rows($product_query) > 0) {
        $product_data = mysqli_fetch_assoc($product_query);

        $product_name = $product_data['name'];
        $product_price = $product_data['price'];
        $product_image = $product_data['image'];
        $available_stock = intval($product_data['stock']);
        $quantity = 1;

        if ($available_stock > 0) {
            // Check if product already exists in cart
            $cart_check = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Query failed');

            if (mysqli_num_rows($cart_check) > 0) {
                echo json_encode(['success' => false, 'message' => 'Product already in cart']);
            } else {
                // Add product to cart
                mysqli_query($conn, "INSERT INTO `cart` (user_id, name, price, quantity, image, barcode) 
                    VALUES ('$user_id', '$product_name', '$product_price', '$quantity', '$product_image', '$barcode')") or die('Query failed');

                // Update stock
                $new_stock = $available_stock - $quantity;
                mysqli_query($conn, "UPDATE `products` SET stock = $new_stock WHERE barcode = '$barcode'") or die('Stock update failed');

                echo json_encode(['success' => true, 'message' => 'Product added to cart']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Out of stock']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid barcode']);
}
?>
