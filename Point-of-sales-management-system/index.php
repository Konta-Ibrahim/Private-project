<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Items Table</title>
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
