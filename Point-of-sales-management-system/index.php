<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Items Table</title>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap 3 CSS et JavaScript, car BootstrapDialog est conçu pour Bootstrap 3 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- BootstrapDialog -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/css/bootstrap-dialog.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Style de base */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4ff;
            color: #333;
        }

        #pos_items_tbl {
            width: 100%;
            max-width: 900px;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        #pos_items_tbl th, #pos_items_tbl td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        #pos_items_tbl th {
            background-color: #e6f2ff;
            color: #007bff;
            font-size: 16px;
        }

        #pos_items_tbl td {
            font-size: 14px;
        }

        .pos_items_btn {
            color: #5cb85c;
            font-size: 20px;
            margin: 0 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .pos_items_btn:hover {
            color: #d9534f;
        }
    </style>
</head>
<body>
<div class="container">
 <div class="row">
<div class="col-md-1">1</div>
 <div class="col-md-1">2</div>

 <div class="col-md-1">3</div>
 <div class="col-md-1">4</div>
 <div class="col-md-1">5</div>
 <div class="col-md-1">6</div>
<div class="col-md-1">7</div>
 <div class="col-md-1">8</div>
 <div class="col-md-1">9</div>
 <div class="col-md-1">10</div>
 <div class="col-md-1">11</div>
 <div class="col-md-1">12</div>
 </div>
 <div class="row">
 <div class="col-md-8">1-8</div>
 <div class="col-md-4">9-12</div>
 </div>
 <div class="row">
 <div class="col-md-4">1-4</div>
 <div class="col-md-4">5-8</div>
<div class="col-md-4">9-12</div>
 </div>
 <div class="row">
 <div class="col-md-6">1-6</div>
 <div class="col-md-6">7-12</div>
 </div>
</div>
<ul class="list-group">
 <li class="list-group-item">First Element
 <div class="btn-group">
 <button type="button"
 class="btn btn-default dropdown-toggle"
 data-toggle="dropdown" aria-haspopup="true"
 aria-expanded="false">
 Aktion <span class="caret"></span>
 </button>
 <ul class="dropdown-menu">
 <li><a href="#">Delete</a></li>
 <li><a href="#">Move</a></li>
 <li><a href="#">Rename</a></li>
 <li role="separator" class="divider"></li>
 <li><a href="#">Download</a></li>
 </ul>
 </div>
 </li>
 <li>... Other elements</li>
 <li>... Other elements</li>
 </ul>
<img src="icon.jpg" onclick="alert('Hello')">
    <h1 style="text-align: center;">Gestion des Produits</h1>
    <table id="pos_items_tbl">
        <thead>
            <tr>
                <th>Nom du Produit</th>
                <th>Prix</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr data-id="1">
                <td>Produit 1</td>
                <td>10.00 €</td>
                <td>
                    <a href="javascript:void(0);" class="pos_items_btn update-btn" data-id="1">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="javascript:void(0);" class="pos_items_btn delete-btn" data-id="1">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            <tr data-id="2">
                <td>Produit 2</td>
                <td>15.00 €</td>
                <td>
                    <a href="javascript:void(0);" class="pos_items_btn update-btn" data-id="2">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="javascript:void(0);" class="pos_items_btn delete-btn" data-id="2">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>

    <script>
        console.log("Hello");
        document.addEventListener("DOMContentLoaded", () => {
            // Gérer les boutons de mise à jour
            document.querySelectorAll(".update-btn").forEach((button) => {
                button.addEventListener("click", (event) => {
                    const itemId = event.target.closest("a").getAttribute("data-id");
                    handleUpdate(itemId);
                });
            });

            // Gérer les boutons de suppression
            document.querySelectorAll(".delete-btn").forEach((button) => {
                button.addEventListener("click", (event) => {
                    const itemId = event.target.closest("a").getAttribute("data-id");
                    handleDelete(itemId);
                });
            });
        });

        // Fonction de mise à jour
        function handleUpdate(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            const productName = row.querySelector("td:first-child").innerText;
            const newPrice = prompt(`Mettre à jour le prix pour "${productName}":`, "0.00");
            if (newPrice && !isNaN(newPrice)) {
                row.querySelector("td:nth-child(2)").innerText = `${parseFloat(newPrice).toFixed(2)} €`;
                alert(`Prix du produit "${productName}" mis à jour avec succès !`);
            }
        }

        // Fonction de suppression
        function handleDelete(id) {
            if (confirm("Êtes-vous sûr de vouloir supprimer cet élément ?")) {
                const row = document.querySelector(`tr[data-id="${id}"]`);
                if (row) {
                    row.remove();
                    alert(`Produit supprimé avec succès.`);
                }
            }
        }
    </script>
</body>
</html>
