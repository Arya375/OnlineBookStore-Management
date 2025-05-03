<?php
session_start();
include("connection.php");

if (!isset($_SESSION["customer_email"])) {
    header("Location: login.php");
    exit();
}

$customer_email = $_SESSION["customer_email"];
$stmt = $conn->prepare("SELECT Customer_id FROM customer WHERE Customer_email = ?");
$stmt->bind_param("s", $customer_email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$customer_id = $row['Customer_id'];

// Fetch unpaid orders
$orders = $conn->query("SELECT * FROM `order` WHERE Customer_id = $customer_id AND Order_status = 'Pending'");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $payment_method = $_POST['payment_method'];
    $amount_query = $conn->query("SELECT Total_price FROM `order` WHERE Order_id = $order_id AND Customer_id = $customer_id");

    if ($amount_query->num_rows > 0) {
        $amount = $amount_query->fetch_assoc()['Total_price'];

        // Insert into payment table
        $stmt = $conn->prepare("INSERT INTO payment (Order_id, Customer_id, Amount, Payment_method, Payment_status) VALUES (?, ?, ?, ?, 'Completed')");
        $stmt->bind_param("iids", $order_id, $customer_id, $amount, $payment_method);

        if ($stmt->execute()) {
            // Update order status
            $stmt = $conn->prepare("UPDATE `order` SET Order_status = 'Paid' WHERE Order_id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();

            echo "<script>alert('Payment successful!'); window.location.href='payment_history.php';</script>";
        } else {
            echo "<script>alert('Payment failed: " . $stmt->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Make Payment</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #34495E;
            color: white;
            padding: 30px;
            border-radius: 10px;
        }
        .form-container select,
        .form-container input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        .form-container select {
            background-color: #ecf0f1;
            color: #2c3e50;
        }
        .form-container input[type="submit"] {
            background-color: #2ecc71;
            color: white;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2 style="text-align:center;color:white;">Make Payment</h2>
    <form method="POST">
        <label for="order_id">Select Pending Order:</label>
        <select name="order_id" required>
            <option value="">-- Select Order --</option>
            <?php while ($row = $orders->fetch_assoc()): ?>
                <option value="<?= $row['Order_id'] ?>">Order #<?= $row['Order_id'] ?> - ₹<?= $row['Total_price'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="payment_method">Payment Method:</label>
        <select name="payment_method" required>
            <option value="">-- Select Method --</option>
            <option value="UPI">UPI</option>
            <option value="Credit Card">Credit Card</option>
            <option value="Debit Card">Debit Card</option>
            <option value="Cash on Delivery">Cash on Delivery</option>
        </select>

        <input type="submit" value="Pay Now">
    </form>
</div>

</body>
</html>
