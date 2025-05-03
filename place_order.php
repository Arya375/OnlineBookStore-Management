<?php
session_start();
include("connection.php");

if (!isset($_SESSION['customer_email'])) {
    header("Location: login.php");
    exit();
}

$customer_email = $_SESSION['customer_email'];
$get_id = $conn->query("SELECT Customer_id FROM customer WHERE Customer_email = '$customer_email'");
$cust = $get_id->fetch_assoc();
$customer_id = $cust['Customer_id'];

$cart_query = $conn->query("
    SELECT c.*, b.Price, b.Title 
    FROM cart c 
    JOIN book b ON c.Book_id = b.Book_id 
    WHERE c.Customer_id = $customer_id
");

$total_price = 0;
$cart_items = [];
while ($item = $cart_query->fetch_assoc()) {
    $item_total = $item['Quantity'] * $item['Price'];
    $total_price += $item_total;
    $cart_items[] = $item;
}

$success = false;
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($cart_items)) {
    $conn->query("INSERT INTO `order` (Customer_id, Total_price, Order_status) VALUES ($customer_id, $total_price, 'Pending')");
    $order_id = $conn->insert_id;

    foreach ($cart_items as $item) {
        $book_id = $item['Book_id'];
        $qty = $item['Quantity'];
        $price = $item['Price'];
        $conn->query("INSERT INTO order_detail (Order_id, Book_id, Quantity, Price) VALUES ($order_id, $book_id, $qty, $price)");
    }

    $conn->query("DELETE FROM cart WHERE Customer_id = $customer_id");
    $success = true;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Place Order</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body{
            margin:0px;
            background-color: #BDC3C7;
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
        .order-container {
            max-width: 1000px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .order-header {
            background-color: #2C3E50;
            color: white;
            padding: 25px;
            text-align: center;
        }
        .order-body {
            padding: 30px;
        }
       
        
        .total-price {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #2C3E50;
        }
        .success-msg, .error-msg {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        .success-msg {
            background-color: #27ae60;
            color: #fff;
        }
        .error-msg {
            font-size:20px;
            color: #c0392b;
        }
        .place-btn {
            background-color: #2C3E50;
            color: white;
            padding: 14px 30px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: block;
            margin: 30px auto 0;
            transition: 0.3s ease;
        }
        .place-btn:hover {
            background-color: #1f618d;
        }
        .browse-link {
            display: inline-block;
            background-color: #34495E;
            color: #fff;
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin: 20px auto 0;
        }
        .browse-link:hover {
            background-color:rgb(119, 173, 227);
        }
        @media (max-width: 768px) {
            .order-body table, table th, table td {
                font-size: 14px;
                padding: 10px;
            }
            .place-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="order-container">
    <div class="order-header">
        <h2>🛒 Confirm Your Order</h2>
    </div>
    <div class="order-body">
        <?php if ($success): ?>
            <div class="success-msg">🎉 Your order has been placed successfully!</div>
        <?php elseif (empty($cart_items)): ?>
            <div class="error-msg">❌ Your cart is empty. Please add books before placing an order.</div>
            <div style="text-align: center;">
                <a class="browse-link" href="browse_books.php">📚 Browse Books</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <table border="1" cellspacing="10" cellpadding="10">
                    <thead>
                        <tr>
                            <th>Book ID</th>
                            <th>Title</th>
                            <th>Quantity</th>
                            <th>Price (₹)</th>
                            <th>Subtotal (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= $item['Book_id'] ?></td>
                            <td><?= htmlspecialchars($item['Title']) ?></td>
                            <td><?= $item['Quantity'] ?></td>
                            <td><?= number_format($item['Price'], 2) ?></td>
                            <td><?= number_format($item['Quantity'] * $item['Price'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="total-price">Total Price: ₹<?= number_format($total_price, 2) ?></div>
                <button type="submit" class="place-btn">✅ Place Order</button>
            </form>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
