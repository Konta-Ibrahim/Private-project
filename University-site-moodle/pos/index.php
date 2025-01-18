<?php include('../includes/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>POS - POS System</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <script src="../assets/js/quagga.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Point de Vente</h1>
        <form id="pos-form">
            <input type="text" id="barcode" class="form-control" placeholder="Scannez ou entrez le code-barres">
            <div id="product-details"></div>
            <button type="submit" class="btn btn-primary">Finaliser la Vente</button>
        </form>
    </div>
    <script>
        document.getElementById('barcode').addEventListener('change', function () {
            const barcode = this.value;
            fetch(`../api/products.php?barcode=${barcode}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('product-details').innerHTML = `
                            <h4>${data.product.name}</h4>
                            <p>Prix: FCFA ${data.product.price}</p>
                        `;
                    } else {
                        alert('Produit non trouvé');
                    }
                });
        });
    </script>
</body>
</html>
