<?php
session_start();
include("connection.php");

// Restrict access to admin only
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Delete customer if requested
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $conn->query("DELETE FROM customer WHERE Customer_id = $delete_id");
}

$result = $conn->query("SELECT * FROM customer");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Customers</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            width: 95%;
            margin: 0 auto;
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
    </style>
</head>
<body>

<div class="container">
    <h1 style="text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: #34495E;
            margin: 20px 0;">All Customers</h1>
    <table border="1" cellpadding="10" cellspacing="10">
        <tr>
            <th>Customer ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['Customer_id'] ?></td>
            <td><?= htmlspecialchars($row['Customer_name']) ?></td>
            <td><?= htmlspecialchars($row['Customer_email']) ?></td>
            <td><a href="?delete=<?= $row['Customer_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this Customer?')">❌ Delete</a></td>

        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
