<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    exit("Access Denied");
}

// Update status if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $update_query = "UPDATE `order` SET Order_status = ? WHERE Order_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Order status updated successfully.');</script>";
}

// Fetch all orders
$order_query = "SELECT o.Order_id, o.Customer_id, o.Order_date, o.Order_status, c.Customer_name AS customer_name 
                FROM `order` o 
                JOIN customer c ON o.Customer_id = c.Customer_id 
                ORDER BY o.Order_date DESC";

$order_result = $conn->query($order_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Order Status</title>
    <style>
        .form-container {
            width: 95%;
            margin: 0 auto;
        }

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
        .status-select {
            padding: 6px 10px;
            background-color: #34495E;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .status-select option {
            background-color: #2C3E50;
            color: white;
        }

        .update-btn {
            padding: 6px 10px;
            background-color: #2C3E50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .update-btn:hover {
            background-color: #1A252F;
        }

        .delete-btn {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-btn:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>

<div class="form-container">
    <h1 style=" text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: #34495E;
            margin: 20px 0;">🚚 Update Order Status</h1>

    <table border="1" cellpadding="10" cellspacing="10">
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Order Date</th>
            <th>Current Status</th>
            <th>Update Status</th>
        </tr>

        <?php while ($row = $order_result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['Order_id'] ?></td>
            <td><?= htmlspecialchars($row['customer_name']) ?></td>
            <td><?= $row['Order_date'] ?></td>
            <td><?= $row['Order_status'] ?></td>
            <td>
                <form method="POST" action="">
                    <input type="hidden" name="order_id" value="<?= $row['Order_id'] ?>">
                    <select name="status" class="status-select" required>
                        <option value="">-- Select Status --</option>
                        <option value="Pending" <?php if($row['Order_status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                        <option value="Shipped" <?php if($row['Order_status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                        <option value="Delivered" <?php if($row['Order_status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                        <option value="Cancelled" <?php if($row['Order_status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                    </select>
                    <button type="submit" name="update_status" class="update-btn">Update</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>
</div>

</body>
</html>
