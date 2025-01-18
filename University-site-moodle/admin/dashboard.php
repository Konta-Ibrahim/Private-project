<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supérette - Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Gestion de la Supérette</h1>

        <!-- Gestion des produits -->
        <h2 class="mt-4">Produits</h2>
        <form id="addProductForm" class="mb-4">
            <div class="form-group">
                <label for="productName">Nom du produit</label>
                <input type="text" id="productName" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="barcode">Code-barres</label>
                <input type="text" id="barcode" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="price">Prix</label>
                <input type="number" id="price" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter le produit</button>
        </form>

        <!-- Liste des produits -->
        <h2>Liste des produits</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Code-barres</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="productList"></tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
