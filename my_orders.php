<?php
session_start();
include("connection.php");

if (!isset($_SESSION["customer_email"])) {
    header("Location: login.php");
    exit();
}

$customer_email = $_SESSION["customer_email"];
$result = $conn->query("SELECT Customer_id FROM customer WHERE Customer_email = '$customer_email'");
$row = $result->fetch_assoc();
$customer_id = $row['Customer_id'];

$orders = $conn->query("SELECT * FROM `order` WHERE Customer_id = $customer_id ORDER BY Order_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <style>
        body {
            margin: 0;
            background-color: #BDC3C7;
        }

        .container {
            width: 95%;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: #34495E;
            margin: 20px 0;
        }

        table {
            width: 100%;
            background-color: #34495E;
            color: white;
        }
        table td, table th {
            padding: 10px;
            text-align: center;
        }

           .no-orders {
            text-align: center;
            color: #e74c3c;
            font-weight: bold;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>📦 My Orders</h1>

    <?php if ($orders->num_rows > 0): ?>
    <table border="1" cellspacing="10" cellpadding="10">
        <tr>
            <th>Order ID</th>
            <th>Total Price</th>
            <th>Order Date</th>
            <th>Status</th>
        </tr>
        <?php while ($order = $orders->fetch_assoc()): ?>
        <tr>
            <td><?= $order['Order_id'] ?></td>
            <td>₹<?= number_format($order['Total_price'], 2) ?></td>
            <td><?= date("d M Y, h:i A", strtotime($order['Order_date'])) ?></td>
            <td><?= htmlspecialchars($order['Order_status']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <div class="no-orders">You haven't placed any orders yet.</div>
    <?php endif; ?>
</div>

</body>
</html>
