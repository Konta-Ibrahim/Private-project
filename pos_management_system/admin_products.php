<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

if (isset($_POST['update_product'])) {
   $update_id = $_POST['update_id'];
   $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
   $update_price = $_POST['update_price'];

   if (isset($_FILES['update_image']['name']) && $_FILES['update_image']['name'] != '') {
       $update_image = $_FILES['update_image']['name'];
       $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
       $update_image_folder = 'uploaded_img/' . $update_image;

       // Supprimer l'ancienne image
       $old_image = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$update_id'");
       $old_image_data = mysqli_fetch_assoc($old_image);
       unlink('uploaded_img/' . $old_image_data['image']);

       // Mettre à jour avec nouvelle image
       move_uploaded_file($update_image_tmp_name, $update_image_folder);
       $update_query = "UPDATE `products` SET name = '$update_name', price = '$update_price', image = '$update_image' WHERE id = '$update_id'";
   } else {
       // Mise à jour sans changer l'image
       $update_query = "UPDATE `products` SET name = '$update_name', price = '$update_price' WHERE id = '$update_id'";
   }

   mysqli_query($conn, $update_query) or die('query failed');
   echo json_encode(['success' => true]);
   exit;
}

// Ajouter un produit
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $stock = $_POST['stock']; // Nouvelle ligne
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    $check_product = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');
    if (mysqli_num_rows($check_product) > 0) {
        $message[] = 'Le produit existe déjà !';
    } else {
        if ($image_size > 2000000) {
            $message[] = 'La taille de l’image est trop grande.';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            mysqli_query($conn, "INSERT INTO `products`(name, price, stock, image) VALUES('$name', '$price', '$stock', '$image')") or die('query failed');
            $message[] = 'Produit ajouté avec succès !';
        }
    }
}


// Supprimer un produit
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $get_image = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
    $image_data = mysqli_fetch_assoc($get_image);
    unlink('uploaded_img/' . $image_data['image']);
    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
    header('location:admin_products.php');
}

// Charger les données pour mise à jour
$update_product_id = '';
if (isset($_GET['update'])) {
    $update_product_id = $_GET['update'];
    $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_product_id'") or die('query failed');
    if (mysqli_num_rows($update_query) > 0) {
        $update_data = mysqli_fetch_assoc($update_query);
    } else {
        $update_product_id = '';
    }
}

// Mettre à jour un produit
if (isset($_POST['update_product'])) {
    $update_id = $_POST['update_id'];
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_price = $_POST['update_price'];
    $update_stock = $_POST['update_stock']; // Nouvelle ligne

    $update_query = "UPDATE `products` SET name = '$update_name', price = '$update_price', stock = '$update_stock' WHERE id = '$update_id'";

    if (!empty($_FILES['update_image']['name'])) {
        $update_image = $_FILES['update_image']['name'];
        $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
        $update_image_folder = 'uploaded_img/' . $update_image;
        $old_image = $_POST['old_image'];
        unlink('uploaded_img/' . $old_image);
        move_uploaded_file($update_image_tmp_name, $update_image_folder);
        $update_query = "UPDATE `products` SET name = '$update_name', price = '$update_price', stock = '$update_stock', image = '$update_image' WHERE id = '$update_id'";
    }

    mysqli_query($conn, $update_query) or die('query failed');
    header('location:admin_products.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Produits</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>

<?php include 'admin_header.php'; ?>

<section class="manage-products">
    <h1 class="title">Gérer les Produits</h1>

    <!-- Formulaire d'ajout de produit -->
    <div class="product-form">
      <form action="" method="post" enctype="multipart/form-data">
        <h3>Ajouter un produit</h3>
           <input type="text" name="name" class="box" placeholder="Nom du produit" required>
           <input type="number" name="price" class="box" placeholder="Prix du produit" min="0" required>
           <input type="number" name="stock" class="box" placeholder="Quantité en stock" min="0" required>
           <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
           <button type="submit" name="add_product" class="btn">Ajouter</button>
      </form>

    </div>

    <!-- Formulaire de mise à jour (affiché si nécessaire) -->
    <?php if (!empty($update_product_id)) { ?>
        <div class="product-form update-form">
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Mettre à jour le produit</h3>
                <input type="hidden" name="update_id" value="<?php echo $update_data['id']; ?>">
                <input type="hidden" name="old_image" value="<?php echo $update_data['image']; ?>">
                <input type="number" name="update_stock" class="box" value="<?php echo $update_data['stock']; ?>" min="0" required>

                <input type="text" name="update_name" class="box" value="<?php echo $update_data['name']; ?>" required>
                <input type="number" name="update_price" class="box" value="<?php echo $update_data['price']; ?>" min="0" required>
                <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
                <button type="submit" name="update_product" class="btn">Mettre à jour</button>
                <a href="admin_products.php" class="btn cancel-btn">Annuler</a>
            </form>
        </div>
    <?php } ?>

    <!-- Liste des produits -->
    <div class="product-list">
        <h3>Produits disponibles</h3>
        <div class="box-container">
            <?php
            $products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            if (mysqli_num_rows($products) > 0) {
                while ($product = mysqli_fetch_assoc($products)) {
                    ?>
                    <div class="box">
                        <img src="uploaded_img/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                        <div class="details">
                            <h4><?php echo $product['name']; ?></h4>
                            <p>Quantité en stock : <?php echo $product['stock']; ?></p>
                            <p>Prix : <?php echo $product['price']; ?> €</p>
                        </div>
                        <div class="actions">
                            <a href="admin_products.php?update=<?php echo $product['id']; ?>" class="update-btn">Modifier</a>
                            <a href="admin_products.php?delete=<?php echo $product['id']; ?>" class="delete-btn" onclick="return confirm('Supprimer ce produit ?');">Supprimer</a>
                        </div>
                    </div>
                <?php
                }
            } else {
                echo '<p class="empty">Aucun produit ajouté.</p>';
            }
            ?>
        </div>
    </div>
</section>

<script src="js/admin_script.js"></script>
</body>
</html>
