<?php
// Include database connection
include 'config.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle debt payment submission
if (isset($_POST['pay_debt'])) {
    $debt_id = $_POST['debt_id'];
    $payment_amount = floatval($_POST['payment_amount']);

    $debt_query = mysqli_query($conn, "SELECT * FROM `debts` WHERE id = '$debt_id'") or die('query failed');
    $debt = mysqli_fetch_assoc($debt_query);

    if ($payment_amount >= $debt['amount_due']) {
        // Complete payment
        mysqli_query($conn, "UPDATE `debts` SET status = 'paid' WHERE id = '$debt_id'") or die('query failed');
        $message = "Dette réglée avec succès !";
    } else {
        // Partial payment
        $remaining_amount = $debt['amount_due'] - $payment_amount;
        mysqli_query($conn, "UPDATE `debts` SET amount_due = '$remaining_amount' WHERE id = '$debt_id'") or die('query failed');
        $message = "Paiement partiel effectué. Il reste " . number_format($remaining_amount, 2) . " € à payer.";
    }

    // Record the payment
    mysqli_query($conn, "INSERT INTO `payments` (user_id, payment_mode, amount) VALUES ('$user_id', 'Debt Payment', '$payment_amount')") or die('query failed');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Dettes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="debts">
<a href="home.php" class="btn-ellipse">home</a>
    <h2>Mes Dettes</h2>
    <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Montant</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $debts_query = mysqli_query($conn, "SELECT * FROM `debts` WHERE user_id = '$user_id'") or die('query failed');
            if (mysqli_num_rows($debts_query) > 0) {
                while ($debt = mysqli_fetch_assoc($debts_query)) {
                    $status = $debt['status'] === 'unpaid' ? 'Impayé' : 'Payé';
                    echo "<tr>
                        <td>{$debt['date']}</td>
                        <td>" . number_format($debt['amount_due'], 2) . " €</td>
                        <td>$status</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Aucune dette enregistrée.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

<section class="pay-debt">
    <h2>Régler une Dette</h2>
    <form action="" method="post">
        <label for="debt_id">Sélectionnez une dette :</label>
        <select name="debt_id" id="debt_id" required>
            <?php
            $debts_query = mysqli_query($conn, "SELECT * FROM `debts` WHERE user_id = '$user_id' AND status = 'unpaid'") or die('query failed');
            while ($debt = mysqli_fetch_assoc($debts_query)) {
                echo "<option value='{$debt['id']}'>Dette de " . number_format($debt['amount_due'], 2) . " € (le {$debt['date']})</option>";
            }
            ?>
        </select>
        <label for="payment_amount">Montant à payer :</label>
        <input type="number" name="payment_amount" step="0.01" min="0.01" required>
        <button type="submit" name="pay_debt" class="btn">Payer</button>
    </form>
</section>

<?php include 'footer.php'; ?>

</body>
</html>
