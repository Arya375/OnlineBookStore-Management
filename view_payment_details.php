<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    exit("Access Denied");
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $payment_id = $_GET['id'];

    // Fetch payment details along with customer and order info
    $stmt = $conn->prepare("SELECT p.Payment_id, p.Amount, p.Payment_method, p.Payment_status, 
                                   p.Order_id, o.Order_date, o.Total_price, 
                                   c.Customer_name, c.Customer_email, c.Contact_no, c.Address 
                            FROM payment p
                            JOIN `order` o ON p.Order_id = o.Order_id
                            JOIN customer c ON o.Customer_id = c.Customer_id
                            WHERE p.Payment_id = ?");
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $payment = $result->fetch_assoc();
    $stmt->close();

    if (!$payment) {
        exit("Payment record not found.");
    }
} else {
    exit("Invalid Payment ID.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Details</title>
    <style>
  .container {
            width: 95%;
            margin: 0 auto;
        }

        body {
            margin: 0;
            background-color: #BDC3C7;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 70%;
            margin: auto;
            padding: 20px;
            background-color: #34495E;
            color: white;
            border-radius: 5px;
        }
       
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .back-btn {
            padding: 8px 15px;
            background-color: #2C3E50;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
        .back-btn:hover {
            background-color: #1A252F;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 style=" text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: white;
            margin: 20px 0;">Payment Details (Payment ID: <?= $payment['Payment_id'] ?>)</h1>
    <table>
        <tr>
            <th>Amount</th>
            <td>$<?= number_format($payment['Amount'], 2) ?></td>
        </tr>
        <tr>
            <th>Payment Method</th>
            <td><?= htmlspecialchars($payment['Payment_method']) ?></td>
        </tr>
        <tr>
            <th>Payment Status</th>
            <td><?= htmlspecialchars($payment['Payment_status']) ?></td>
        </tr>
        <tr>
            <th>Order ID</th>
            <td><?= $payment['Order_id'] ?></td>
        </tr>
        <tr>
            <th>Order Date</th>
            <td><?= $payment['Order_date'] ?></td>
        </tr>
        <tr>
            <th>Total Order Price</th>
            <td>$<?= number_format($payment['Total_price'], 2) ?></td>
        </tr>
        <tr>
            <th>Customer Name</th>
            <td><?= htmlspecialchars($payment['Customer_name']) ?></td>
        </tr>
        <tr>
            <th>Customer Email</th>
            <td><?= htmlspecialchars($payment['Customer_email']) ?></td>
        </tr>
        <tr>
            <th>Customer Contact</th>
            <td><?= htmlspecialchars($payment['Contact_no']) ?></td>
        </tr>
        <tr>
            <th>Customer Address</th>
            <td><?= nl2br(htmlspecialchars($payment['Address'])) ?></td>
        </tr>
    </table>

    <a href="view_payments.php" class="back-btn">Back to Payments</a>
</div>

</body>
</html>
