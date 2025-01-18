<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit;
}

// Ajouter un produit
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $barcode = mysqli_real_escape_string($conn, $_POST['barcode']);
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . basename($image);

    if (!file_exists('uploaded_img')) {
        mkdir('uploaded_img', 0777, true);
    }

    $check_product = mysqli_query($conn, "SELECT barcode FROM `products` WHERE barcode = '$barcode'") or die('Query failed');
    if (mysqli_num_rows($check_product) > 0) {
        $message[] = 'Le produit avec ce code-barres existe déjà !';
    } elseif (!in_array(pathinfo($image, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
        $message[] = 'Veuillez télécharger une image valide (jpg, jpeg, png).';
    } elseif ($_FILES['image']['size'] > 2000000) {
        $message[] = 'La taille de l’image est trop grande.';
    } else {
        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            mysqli_query($conn, "INSERT INTO `products`(name, price, stock, image, barcode) VALUES('$name', '$price', '$stock', '$image', '$barcode')") or die('Query failed');
            $message[] = 'Produit ajouté avec succès !';
        } else {
            $message[] = 'Erreur lors du téléchargement de l’image.';
        }
    }
}

// Modifier un produit
if (isset($_POST['update_product'])) {
    $update_id = $_POST['update_id'];
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_price = $_POST['update_price'];
    $update_stock = $_POST['update_stock'];
    $update_barcode = mysqli_real_escape_string($conn, $_POST['update_barcode']);

    mysqli_query($conn, "UPDATE `products` SET name='$update_name', price='$update_price', stock='$update_stock', barcode='$update_barcode' WHERE id='$update_id'") or die('Query failed');

    if (!empty($_FILES['update_image']['name'])) {
        $update_image = $_FILES['update_image']['name'];
        $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
        $update_image_folder = 'uploaded_img/' . basename($update_image);

        $get_image = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$update_id'") or die('Query failed');
        $image_data = mysqli_fetch_assoc($get_image);
        $old_image_path = 'uploaded_img/' . $image_data['image'];
        if (file_exists($old_image_path)) {
            unlink($old_image_path);
        }

        if (move_uploaded_file($update_image_tmp_name, $update_image_folder)) {
            mysqli_query($conn, "UPDATE `products` SET image='$update_image' WHERE id='$update_id'") or die('Query failed');
        }
    }

    $message[] = 'Produit mis à jour avec succès !';
}

// Supprimer un produit
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $get_image = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('Query failed');
    if ($image_data = mysqli_fetch_assoc($get_image)) {
        $image_path = 'uploaded_img/' . $image_data['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('Query failed');
    header('location:admin_products.php');
    exit;
}

// Recherche de produit
$search_query = "";
if (isset($_POST['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_POST['search_query']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Produits</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="manage-products">
    <h1 class="title">Gérer les Produits</h1>

    <!-- Formulaire d'ajout de produit -->
    <form action="" method="post" enctype="multipart/form-data">
        <h3>Ajouter un produit</h3>
        <input type="text" name="name" class="box" placeholder="Nom du produit" required>
        <input type="number" name="price" class="box" placeholder="Prix du produit" min="0" required>
        <input type="number" name="stock" class="box" placeholder="Quantité en stock" min="0" required>
        <input type="text" name="barcode" class="box" placeholder="Code-barres" required>
        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
        <button type="submit" name="add_product" class="btn">Ajouter</button>
    </form>

    <!-- Formulaire de recherche -->
    <form action="" method="post" class="search-form">
        <input type="text" name="search_query" class="box" placeholder="Rechercher un produit" value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit" name="search" class="btn">Rechercher</button>
    </form>

    <!-- Liste des produits -->
    <div class="product-list">
        <h3>Produits disponibles</h3>
        <div class="box-container">
            <?php
            $query = "SELECT * FROM `products`";
            if (!empty($search_query)) {
                $query .= " WHERE name LIKE '%$search_query%' OR barcode LIKE '%$search_query%'";
            }
            $products = mysqli_query($conn, $query) or die('Query failed');
            if (mysqli_num_rows($products) > 0) {
                while ($product = mysqli_fetch_assoc($products)) {
                    ?>
                    <div class="box">
                        <img src="uploaded_img/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="details">
                            <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                            <p>Quantité en stock : <?php echo htmlspecialchars($product['stock']); ?></p>
                            <p>Prix : <?php echo htmlspecialchars($product['price']); ?> €</p>
                        </div>
                        <div class="actions">
                            <button onclick="openUpdateForm(<?php echo $product['id']; ?>, '<?php echo addslashes($product['name']); ?>', <?php echo $product['price']; ?>, <?php echo $product['stock']; ?>, '<?php echo addslashes($product['barcode']); ?>')" class="btn">Modifier</button>
                            <a href="admin_products.php?delete=<?php echo $product['id']; ?>" class="btn" onclick="return confirm('Supprimer ce produit ?');">Supprimer</a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="empty">Aucun produit trouvé.</p>';
            }
            ?>
        </div>
    </div>

    <!-- Formulaire de mise à jour (caché par défaut) -->
    <form action="" method="post" enctype="multipart/form-data" id="update-form" style="display:none;">
        <h3>Modifier le produit</h3>
        <input type="hidden" name="update_id" id="update-id">
        <input type="text" name="update_name" id="update-name" class="box" placeholder="Nom du produit" required>
        <input type="number" name="update_price" id="update-price" class="box" placeholder="Prix du produit" min="0" required>
        <input type="number" name="update_stock" id="update-stock" class="box" placeholder="Quantité en stock" min="0" required>
        <input type="text" name="update_barcode" id="update-barcode" class="box" placeholder="Code-barres" required>
        <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
        <button type="submit" name="update_product" class="btn">Mettre à jour</button>
    </form>

</section>

<script>
function openUpdateForm(id, name, price, stock, barcode) {
    document.getElementById('update-id').value = id;
    document.getElementById('update-name').value = name;
    document.getElementById('update-price').value = price;
    document.getElementById('update-stock').value = stock;
    document.getElementById('update-barcode').value = barcode;
    document.getElementById('update-form').style.display = 'block';
    window.scrollTo(0, document.getElementById('update-form').offsetTop);
}
</script>

</body>
</html>
