<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    exit("Access Denied");
}

// Deleting a payment record
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM payment WHERE Payment_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Fetching payment details from the database
$result = $conn->query("SELECT p.Payment_id, p.Amount, p.Payment_method, p.Payment_status, o.Order_id, c.Customer_name, p.Payment_id
                        FROM payment p
                        JOIN `order` o ON p.Order_id = o.Order_id
                        JOIN customer c ON o.Customer_id = c.Customer_id
                        ORDER BY p.Payment_id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Payments</title>
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
        

        .delete-btn {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }
        .delete-btn:hover {
            text-decoration: underline;
        }
        .view-btn {
            padding: 6px 10px;
            background-color: #2C3E50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .view-btn:hover {
            background-color: #1A252F;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h1 style=" text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: #34495E;
            margin: 20px 0;">💳 View Payments</h1>
    <table border="1" cellspacing="10" cellpadding="10">
        <tr>
            <th>Payment ID</th>
            <th>Customer Name</th>
            <th>Order ID</th>
            <th>Amount</th>
            <th>Payment Method</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['Payment_id'] ?></td>
            <td><?= htmlspecialchars($row['Customer_name']) ?></td>
            <td><?= $row['Order_id'] ?></td>
            <td>$<?= number_format($row['Amount'], 2) ?></td>
            <td><?= htmlspecialchars($row['Payment_method']) ?></td>
            <td><?= htmlspecialchars($row['Payment_status']) ?></td>
            <td>
                <a href="view_payment_details.php?id=<?= $row['Payment_id'] ?>" class="view-btn">View Details</a>
                <a href="?delete=<?= $row['Payment_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this payment?')">❌ Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
