<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Facturation</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.7/html5-qrcode.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            border: 2px solid #000;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .login-info {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            line-height: 50px;
            cursor: pointer;
        }

        .round-buttons {
            position: absolute;
            top: 100px;
            left: 20px;
        }

        .round-buttons button {
            display: block;
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
            border-radius: 50%;
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .billing-info {
            position: absolute;
            top: 20px;
            left: 100px;
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #f8f9fa;
        }

        .total-container {
            position: absolute;
            top: 20px;
            right: 100px;
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #f8f9fa;
        }

        .calculator {
            position: absolute;
            bottom: 20px;
            right: 100px;
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="login-info" onclick="location.href='login.php';">
        Info
    </div>

    <div class="round-buttons">
        <button>Btn1</button>
        <button>Btn2</button>
        <button>Btn3</button>
    </div>

    <div class="billing-info">
        <h3>Informations de Facturation</h3>
        <form action="" method="post">
            <input type="text" name="name" placeholder="Nom" class="box">
            <input type="text" name="barcode" placeholder="Code-barres" class="box" required>
            <input type="number" name="price" placeholder="Prix" class="box" step="0.01" min="0">
            <button type="submit" name="add_to_invoice" class="btn">Ajouter au Panier</button>
        </form>
        <button onclick="printReceipt()" class="btn">Imprimer le Reçu</button>
    </div>

    <div class="total-container">
        <h3>Total</h3>
        <p>Montant Total: <span id="total-price">0.00 €</span></p>
        <div>
            <button class="btn">Espèces</button>
            <button class="btn">Orange Money</button>
            <input type="number" id="currency-exchange" placeholder="Entrer monnaie" class="box" step="0.01" min="0">
        </div>
        <button onclick="validateTransaction()" class="btn">Valider</button>
    </div>

    <section class="calculator">
        <h3>Calculatrice</h3>
        <input type="text" id="calc-display" class="calc-display" readonly>
        <div class="calc-buttons">
            <button onclick="appendCalcValue('7')">7</button>
            <button onclick="appendCalcValue('8')">8</button>
            <button onclick="appendCalcValue('9')">9</button>
            <button onclick="performCalcOperation('/')">/</button>
            <button onclick="appendCalcValue('4')">4</button>
            <button onclick="appendCalcValue('5')">5</button>
            <button onclick="appendCalcValue('6')">6</button>
            <button onclick="performCalcOperation('*')">*</button>
            <button onclick="appendCalcValue('1')">1</button>
            <button onclick="appendCalcValue('2')">2</button>
            <button onclick="appendCalcValue('3')">3</button>
            <button onclick="performCalcOperation('-')">-</button>
            <button onclick="appendCalcValue('0')">0</button>
            <button onclick="clearCalc()">C</button>
            <button onclick="calculateResult()">=</button>
            <button onclick="performCalcOperation('+')">+</button>
        </div>
    </section>

    <script>
        function printReceipt() {
            alert("Reçu imprimé !");
        }

        function validateTransaction() {
            const total = document.getElementById("total-price").innerText;
            const currency = document.getElementById("currency-exchange").value;
            if (parseFloat(currency) >= parseFloat(total)) {
                alert("Transaction validée !");
            } else {
                alert("Montant insuffisant !");
            }
        }

        function appendCalcValue(value) {
            document.getElementById("calc-display").value += value;
        }

        function performCalcOperation(op) {
            document.getElementById("calc-display").value += op;
        }

        function clearCalc() {
            document.getElementById("calc-display").value = "";
        }

        function calculateResult() {
            const display = document.getElementById("calc-display");
            try {
                display.value = eval(display.value);
            } catch (e) {
                alert("Erreur de calcul !");
            }
        }
    </script>
</body>

</html>
