<?php
session_start();
include("connection.php");

// Admin login validation
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $order_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM `order` WHERE Order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all orders with customer name
$query = "SELECT o.*, c.Customer_name 
          FROM `order` o 
          JOIN customer c ON o.Customer_id = c.Customer_id 
          ORDER BY o.Order_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Orders</title>
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

        .delete-btn {
            color: red;
            font-weight: bold;
            text-decoration: none;
        }

        .delete-btn:hover {
            text-decoration: underline;
        }

        .update-btn {
            color: limegreen;
            font-weight: bold;
            text-decoration: none;
        }

        .update-btn:hover {
            text-decoration: underline;
        }

        .container {
            width: 95%;
            margin: 0 auto;
        }

        
    </style>
</head>
<body>
    <div class="container">
    <h1 style=" text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: #34495E;
            margin: 20px 0;">📦 All Orders</h1>
    <table border="1" cellpadding="10" cellspacing="10">
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Total Price</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($order = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $order['Order_id'] ?></td>
            <td><?= htmlspecialchars($order['Customer_name']) ?></td>
            <td>₹<?= $order['Total_price'] ?></td>
            <td><?= $order['Order_date'] ?></td>
            <td><?= $order['Order_status'] ?></td>
            <td>
                <a href="update_order_status.php?id=<?= $order['Order_id'] ?>" class="update-btn">✏️ Update</a> |
                <a href="?delete=<?= $order['Order_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this order?')">❌ Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
        </div>
</body>
</html>
