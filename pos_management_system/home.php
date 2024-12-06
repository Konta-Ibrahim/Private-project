<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = intval($_POST['product_quantity']);

    // Récupérer les informations sur le produit
    $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE name = '$product_name'") or die('Query failed');
    if (mysqli_num_rows($product_query) > 0) {
        $product_data = mysqli_fetch_assoc($product_query);
        $available_stock = intval($product_data['stock']); // Stock actuel

        // Vérifier si la quantité demandée est disponible
        if ($product_quantity <= $available_stock) {
            // Vérifier si l'utilisateur a déjà ajouté ce produit dans le panier
            $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Query failed');

            if (mysqli_num_rows($check_cart_numbers) > 0) {
                $message[] = 'Product already added to cart!';
            } else {
                // Ajouter au panier
                mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('Query failed');

                // Déduire la quantité du stock
                $new_stock = $available_stock - $product_quantity;
                mysqli_query($conn, "UPDATE `products` SET stock = '$new_stock' WHERE name = '$product_name'") or die('Query failed');

                $message[] = 'Product added to cart!';
            }
        } else {
            // Si la quantité demandée dépasse le stock disponible
            $message[] = 'Not enough stock available!';
        }
    } else {
        $message[] = 'Product not found!';
    }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap CSS and JavaScript -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- SweetAlert and BootstrapDialog -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/css/bootstrap-dialog.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="products">
    <h1 class="title">All Products</h1>

    <div class="box-container">
        <div class="container-fluid">
            <div class="row">
                <!-- Product Grid Section -->
                <div class="col-md-8">
                    <div class="SearchInputContainer">
                        <input type="text" placeholder="Search product ...">
                    </div>
                    <div class="SearchResultContainer">
                        <div class="row grid-layout-container">
                            <?php  
                                $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                                if (mysqli_num_rows($select_products) > 0) {
                                    while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                            ?>
                            <div class="col-md-4 productColContainer">
                                <form action="" method="post" class="ProductResultContainer">
                                    <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                                    <div class="ProductInfoContainer">
                                        <p class="ProductName"><?php echo $fetch_products['name']; ?></p>
                                        <p class="ProductPrice">$<?php echo $fetch_products['price']; ?>/-</p>
                                        <p class="ProductStock">Stock: <?php echo $fetch_products['stock']; ?></p>
                                        <input type="number" min="1" name="product_quantity" value="1" class="qty" style="visibility: hidden;">
                                        <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                                        <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                                        <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                                        <input type="submit" value="Add to Cart" name="add_to_cart" class="btn1">
                                    </div>
                                </form>
                            </div>
                            <?php
                                    }
                                } else {
                                    echo '<p class="empty">No products added yet!</p>';
                                }
                            ?>
                        </div>
                    </div>
                    
                </div>
                
               <!-- Cart Section -->
<div class="col-md-4 posOrderContainer">
    <div class="pos_header">
        <div class="setting AlignRight">
            <a href="javascript:void(0);"><i class="fa fa-gear"></i></a>
        </div>
        <p class="logo">IMS</p>
        <p class="TimeAndDate"><?php echo date("F d, Y h:i:s A"); ?></p>
    </div>
    <div class="pos_items_container">
        <h1 style="text-align: center;">Shopping Cart</h1>

        <?php
        // Traitement des mises à jour des quantités
        if (isset($_POST['update_quantity'])) {
            $cart_id = intval($_POST['cart_id']);
            $quantity = intval($_POST['quantity']);
            
            // Get the product name and current quantity from the cart
            $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE id = '$cart_id'") or die('query failed');
            $cart_item = mysqli_fetch_assoc($cart_query);
            $product_name = $cart_item['name'];
            $old_quantity = $cart_item['quantity'];
        
            // Fetch product stock
            $stock_query = mysqli_query($conn, "SELECT stock FROM `products` WHERE name = '$product_name'") or die('query failed');
            $fetch_stock = mysqli_fetch_assoc($stock_query);
            $available_stock = $fetch_stock['stock'];
        
            // If the new quantity is greater than available stock, show an error
            if ($quantity > $available_stock) {
                echo '<div class="alert alert-danger">Not enough stock available.</div>';
            } elseif ($quantity > 0) {
                // Update the cart quantity
                $update_cart_query = mysqli_query($conn, "UPDATE `cart` SET quantity = '$quantity' WHERE id = '$cart_id'") or die('query failed');
                
                // Update the product stock in the `products` table
                $new_stock = $available_stock + ($old_quantity - $quantity);  // Adjust stock based on change in quantity
                mysqli_query($conn, "UPDATE `products` SET stock = '$new_stock' WHERE name = '$product_name'") or die('query failed');
                
                echo '<div class="alert alert-success">Cart updated successfully!</div>';
            } else {
                echo '<div class="alert alert-danger">Invalid quantity provided.</div>';
            }
        }
        
        

        // Traitement de la suppression d'article
        if (isset($_GET['delete_cart_id'])) {
            $cart_id = intval($_GET['delete_cart_id']);
            // Delete item from the cart
            $delete_query = mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$cart_id'") or die('query failed');
            if ($delete_query) {
                echo '<div class="alert alert-success">Item removed from cart.</div>';
            } else {
                echo '<div class="alert alert-danger">Failed to delete item.</div>';
            }
        }
        
        ?>

        <div class="pos_items">
            <table class="table" id="pos_items_tbl">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
    $total = 0;
    $counter = 1;
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $subtotal = $cart_item['price'] * $cart_item['quantity'];
            $total += $subtotal;
    ?>
    <tr>
        <td><?php echo $counter++; ?></td>
        <td><?php echo htmlspecialchars($cart_item['name']); ?></td>
        <td>$<?php echo htmlspecialchars($cart_item['price']); ?></td>
        <td>
            <!-- Champ pour modifier la quantité -->
            <form method="POST" id="form-<?php echo $cart_item['id']; ?>" style="display: flex;">
                <input type="hidden" name="cart_id" value="<?php echo $cart_item['id']; ?>">
                <input type="number" name="quantity" value="<?php echo $cart_item['quantity']; ?>" min="1" class="form-control quantity-input">
            </form>
        </td>
        <td>$<?php echo number_format($subtotal, 2); ?></td>
        <td>
            <!-- Bouton Save et Trash -->
            <button type="submit" form="form-<?php echo $cart_item['id']; ?>" name="update_quantity" class="btn btn-sm btn-success"><i class="fa fa-save"></i></button>
            <a href="?delete_cart_id=<?php echo $cart_item['id']; ?>" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
        </td>
    </tr>
    <?php
        }
    } else {
        echo '<tr><td colspan="6" class="text-center">Your cart is empty!</td></tr>';
    }
    ?>
</tbody>

            </table>
        </div>
        <div class="items_total_container">
            <p class="items_total">
                <span class="item_total_label">Total</span>
                <span class="item_total_value">$<?php echo number_format($total, 2); ?></span>
            </p>
        </div>
    </div>
    <div class="CheckoutBtnContainer">
        <a href="checkout.php" class="checkoutbtn">CHECKOUT</a>
    </div>
</div>

            </div>
        </div>
    </div>
</section>

<section class="home-contact">
    <div class="content">
        <h3>Have any questions?</h3>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Atque cumque exercitationem repellendus, amet ullam voluptatibus?</p>
        <a href="contact.php" class="white-btn">Contact Us</a>
    </div>
</section>

<!-- Custom JS -->
<script src="js/script.js"></script>

</body>
</html>
