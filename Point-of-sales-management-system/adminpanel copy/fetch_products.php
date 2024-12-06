<?php
// Configuration de la base de données
$host = 'localhost';
$dbname = 'db_pos';
$username = 'root'; // Changez si votre MySQL a un utilisateur différent
$password = 'Konta009%'; // Changez si votre MySQL a un mot de passe

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les produits
    $stmt = $pdo->prepare("SELECT id, product_name, description, img, stock FROM products");
    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Renvoie les données au format JSON
    header('Content-Type: application/json');
    echo json_encode($products);
} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>
