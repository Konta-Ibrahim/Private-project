<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

// Récupérer les informations de l'utilisateur
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'") or die('query failed');
$user = mysqli_fetch_assoc($user_query);

// Make sure quantity is always set
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Default to 1 if not set

if (isset($_POST['add_to_invoice'])) {
    $barcode = mysqli_real_escape_string($conn, $_POST['barcode']);

    $product_query = mysqli_query($conn, "SELECT * FROM products WHERE barcode = '$barcode'") or die('query failed');

    if (mysqli_num_rows($product_query) > 0) {
        $product = mysqli_fetch_assoc($product_query);

        if ($quantity > $product['stock']) {
            $message[] = 'Quantité non disponible en stock !';
        } else {
            $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id' AND barcode = '$barcode'") or die('query failed');

            if (mysqli_num_rows($cart_query) > 0) {
                mysqli_query($conn, "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = '$user_id' AND barcode = '$barcode'") or die('query failed');
            } else {
                mysqli_query($conn, "INSERT INTO cart(user_id, name, price, quantity, image, barcode) VALUES('$user_id', '{$product['name']}', '{$product['price']}', '$quantity', '{$product['image']}', '$barcode')") or die('query failed');
            }

            mysqli_query($conn, "UPDATE products SET stock = stock - $quantity WHERE barcode = '$barcode'") or die('query failed');
        }
    } else {
        $message[] = 'Produit introuvable !';
    }
}

if (isset($_GET['remove'])) {
    $cart_id = $_GET['remove'];
    $cart_item_query = mysqli_query($conn, "SELECT * FROM cart WHERE id = '$cart_id'") or die('query failed');

    if (mysqli_num_rows($cart_item_query) > 0) {
        $cart_item = mysqli_fetch_assoc($cart_item_query);
        $barcode = $cart_item['barcode'];
        $quantity = $cart_item['quantity'];

        mysqli_query($conn, "DELETE FROM cart WHERE id = '$cart_id'") or die('query failed');
        mysqli_query($conn, "UPDATE products SET stock = stock + $quantity WHERE barcode = '$barcode'") or die('query failed');
    }
}

// Handle update quantity
if (isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $new_quantity = $_POST['new_quantity'];

    // Fetch current cart item to get product info
    $cart_item_query = mysqli_query($conn, "SELECT * FROM cart WHERE id = '$cart_id'") or die('query failed');
    $cart_item = mysqli_fetch_assoc($cart_item_query);
    $barcode = $cart_item['barcode'];

    // Check if new quantity is valid and available
    $product_query = mysqli_query($conn, "SELECT * FROM products WHERE barcode = '$barcode'") or die('query failed');
    $product = mysqli_fetch_assoc($product_query);

    if ($new_quantity <= $product['stock'] && $new_quantity > 0) {
        $quantity_diff = $new_quantity - $cart_item['quantity'];
        mysqli_query($conn, "UPDATE cart SET quantity = '$new_quantity' WHERE id = '$cart_id'") or die('query failed');
        mysqli_query($conn, "UPDATE products SET stock = stock - $quantity_diff WHERE barcode = '$barcode'") or die('query failed');
    } else {
        $message[] = 'Quantité invalide ou insuffisante en stock.';
    }
}

if (isset($_POST['validate'])) {
    $payment_mode = mysqli_real_escape_string($conn, $_POST['payment_mode']);
    $amount_given = floatval($_POST['amount_given']);
    $grand_total_query = mysqli_query($conn, "SELECT SUM(price * quantity) AS total FROM cart WHERE user_id = '$user_id'") or die('query failed');
    $grand_total_data = mysqli_fetch_assoc($grand_total_query);
    $grand_total = floatval($grand_total_data['total']);

    if ($amount_given >= $grand_total) {
        $change = $amount_given - $grand_total;

        // Enregistrer le paiement
        mysqli_query($conn, "INSERT INTO payments (user_id, payment_mode, amount) VALUES ('$user_id', '$payment_mode', '$grand_total')") or die('query failed');

        // Vider le panier
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'") or die('query failed');

        if ($change > 0) {
            $message[] = "Paiement accepté ! Rendu au client : " . number_format($change, 2) . " €.";
        } else {
            $message[] = "Paiement accepté ! Aucun rendu nécessaire.";
        }
    } else {
        $missing_amount = $grand_total - $amount_given;

        // Enregistrer la dette
        mysqli_query($conn, "INSERT INTO debts (user_id, amount_due) VALUES ('$user_id', '$missing_amount')") or die('query failed');

        // Enregistrer le paiement partiel
        if ($amount_given > 0) {
            mysqli_query($conn, "INSERT INTO payments (user_id, payment_mode, amount) VALUES ('$user_id', '$payment_mode', '$amount_given')") or die('query failed');
        }

        // Vider le panier
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'") or die('query failed');

        $message[] = "Paiement partiel enregistré. Il reste une dette de " . number_format($missing_amount, 2) . " €.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Facturation</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.7/html5-qrcode.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    
<?php include "header.php"?>

<section class="billing">
        <div class="main-container">
            <div class="cart-container">
                <div class="sous-panels">
                    <div class="item header">
                        <form action="" method="post" class="scanner-form">
                            <div id="qr-reader" style="width: 300px;"></div>
                            <input type="text" id="barcode" name="barcode" class="box" placeholder="Code-barres" required>
                            <button type="submit" name="add_to_invoice" class="btn">Ajouter au Panier</button>

                            
                        
                        </form>
                        <!-----inclure le button ici--->


<div class="e-btn">
    <button class="ellipse-btn">Bouton 1</button>
    <button class="ellipse-btn">Bouton 2</button>
    <button class="ellipse-btn">Bouton 3</button>
    <button class="ellipse-btn">Bouton 4</button>
</div>
<button id="toggle-buttons">+</button>

<button id="toggle-theme">Mode Sombre</button> <!-- Bouton pour basculer entre les modes -->

                    </div>
                    
                         
                  
                    <div class="item content-1">
                        <table>
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Nom</th>
                                    <th>Prix</th>
                                    <th>Quantité</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('query failed');
                                $grand_total = 0;
                                if (mysqli_num_rows($cart_query) > 0) {
                                    while ($cart_item = mysqli_fetch_assoc($cart_query)) {
                                        $subtotal = $cart_item['price'] * $cart_item['quantity'];
                                        $grand_total += $subtotal;
                                        ?>
                                        <tr>
                                            <td><img src="uploaded_img/<?php echo $cart_item['image']; ?>" alt="<?php echo $cart_item['name']; ?>" class="cart-item-img"></td>
                                            <td><?php echo $cart_item['name']; ?></td>
                                            <td><?php echo $cart_item['price']; ?> €</td>
                                            <td>
                                                <form action="" method="post">
                                                    <input type="number" name="new_quantity" value="<?php echo $cart_item['quantity']; ?>" min="1" max="<?php echo $product['stock']; ?>" required>
                                                    <input type="hidden" name="cart_id" value="<?php echo $cart_item['id']; ?>">
                                                    <button type="submit" name="update_quantity" class="btn">
                                                        <i class="fa fa-sync-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td><?php echo $subtotal; ?> €</td>
                                            <td><a href="?remove=<?php echo $cart_item['id']; ?>" class="btn delete-btn">
                                                <i class="fa fa-trash"></i>
                                            </a></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="empty">Aucun produit ajouté.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="item sidebar">
                    
                    <div class="Cash">
    <label for="balance">Balance due</label>
    <input type="text" id="balance" class="input">
    
    <label for="amount">Amount tendered</label>
    <input type="text" id="amount" class="input">
    
    <label for="change">Change</label>
    <input type="text" id="change" class="input">
</div>
                        <div class="monnaie">
                            <p><strong>Grand Total : </strong> <span class="grand-total"><?php echo $grand_total; ?>  €</span></p>
                            <br>
                            
                            <p><strong>Sub-total :</strong> <span class="grand-total"><?php echo $grand_total; ?> €</span></p>
                            <br>
                            <p>Discount (%): <span><input type="number" id="discount" name="discount" min="0" max="100" step="0.01" placeholder="0"></span></p>
                            <p>Tax : <span><input type="number" id="tax" name="tax" min="0" step="0.01" placeholder="0"></span></p>
                        </div>
                    </div>
                    
                    <div class="item content-3">
    
    <div class="payment-methods">
        <div class="payment-column">
            <button class="payment-btn" onclick="showPaymentDialog('Cash')">
                <i class="fas fa-money-bill-wave"></i> Cash
            </button>
            <button class="payment-btn" onclick="showPaymentDialog('Orange Money')">
                <i class="fas fa-mobile-alt"></i> Orange Money
            </button>
        </div>
        <div class="payment-column">
            <button class="payment-btn" onclick="showPaymentDialog('Wave')">
                <i class="fas fa-wave-square"></i> Wave
            </button>
            <button class="payment-btn" onclick="showPaymentDialog('Carte Bancaire')">
                <i class="fas fa-credit-card"></i> Carte Bancaire
            </button>
        </div>
        <div class="payment-column">
            <button class="payment-btn" onclick="showPaymentDialog('PayPal')">
                <i class="fab fa-paypal"></i> PayPal
            </button>
            <button class="payment-btn" onclick="showPaymentDialog('Virement Bancaire')">
                <i class="fas fa-university"></i> Virement Bancaire
            </button>
        </div>
        <!-- Bouton rond -->
        <button class="round-btn" onclick="togglePaymentMethods()">
            <i class="fas fa-check" style="font-size: 50px;"></i>
        </button>
    </div>
</div>
                    
                </div>
            </div>
            <div class="side-panels-container">
                <div class="side-panels">
                    <class="sous-panels">
                        
                        
                           
                       
                        <div class="item content-1">
                            <div class="calculator">
                                <input type="text" id="calc-display" readonly />
                                <div class="calc-buttons">
                                    <button onclick="appendNumber('7')">7</button>
                                    <button onclick="appendNumber('8')">8</button>
                                    <button onclick="appendNumber('9')">9</button>
                                    <button onclick="appendOperator('/')">/</button>

                                    <button onclick="appendNumber('4')">4</button>
                                    <button onclick="appendNumber('5')">5</button>
                                    <button onclick="appendNumber('6')">6</button>
                                    <button onclick="appendOperator('*')">*</button>

                                    <button onclick="appendNumber('1')">1</button>
                                    <button onclick="appendNumber('2')">2</button>
                                    <button onclick="appendNumber('3')">3</button>
                                    <button onclick="appendOperator('-')">-</button>

                                    <button onclick="appendNumber('0')">0</button>
                                    <button onclick="appendDot()">.</button>
                                    <button onclick="clearDisplay()">C</button>
                                    <button onclick="appendOperator('+')">+</button>

                                    <button onclick="calculate()" class="equals">=</button>
                                </div>
                            </div>
                        </div>
                        <br>
                        
                        <div class="item footer">
                            <div class="ellipse-buttons">
                                <button class="ellipse-btn">Bouton 1</button>
                                <button class="ellipse-btn">Bouton 2</button>
                                <button class="ellipse-btn">Bouton 3</button>
                                <button class="ellipse-btn">Bouton 4</button>
                                <button class="ellipse-btn">Bouton 5</button>
                                <button class="ellipse-btn">Bouton 6</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reçu caché pour l'impression -->
    <div id="receipt" style="display:none;">
        <h2>Nom de la Boutique</h2>
        <p>Téléphone: +123 456 789</p>
        <p>Numéro de Ticket: <?php echo uniqid(); ?></p>
        <p>Nom de l'utilisateur: <?php echo $user['name']; ?></p>
        <p>Date: <?php echo date('d/m/Y H:i:s'); ?></p>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('query failed');
                if (mysqli_num_rows($cart_query) > 0) {
                    while ($cart_item = mysqli_fetch_assoc($cart_query)) {
                        $subtotal = $cart_item['price'] * $cart_item['quantity'];
                        ?>
                        <tr>
                            <td><?php echo $cart_item['name']; ?></td>
                            <td><?php echo $cart_item['quantity']; ?></td>
                            <td><?php echo $cart_item['price']; ?> €</td>
                            <td><?php echo $subtotal; ?> €</td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <p>Total: <?php echo $grand_total; ?> €</p>
        <p>Mode de Paiement: <?php echo $_POST['payment_mode'] ?? 'Non spécifié'; ?></p>
        <p>Merci pour votre achat !</p>
    </div>



<script src="js/script.js"></script>
</body>

</html>