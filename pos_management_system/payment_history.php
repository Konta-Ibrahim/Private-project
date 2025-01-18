<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

$payments_query = mysqli_query($conn, "SELECT * FROM `payments` WHERE user_id = '$user_id' ORDER BY date DESC") or die('query failed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Paiements</title>
</head>
<body>
    <h1>Historique des Paiements</h1>
    <table border="1">
        <tr>
            <th>Date</th>
            <th>Mode de Paiement</th>
            <th>Montant</th>
        </tr>
        <?php while ($payment = mysqli_fetch_assoc($payments_query)) { ?>
            <tr>
                <td><?php echo $payment['date']; ?></td>
                <td><?php echo $payment['payment_mode']; ?></td>
                <td><?php echo $payment['amount']; ?> €</td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
