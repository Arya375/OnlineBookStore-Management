<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    exit("Access Denied");
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM contact WHERE Contact_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT * FROM contact ORDER BY submitted_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Contact Messages</title>
    <style>
        .form-container {
            width: 95%;
            margin: 0 auto;
        }

        body {
            margin: 0px;
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
    </style>
</head>
<body>
<div class="form-container">
    <h1 style="text-align: center;
               font-style: italic;
               text-decoration: underline;
               color: #34495E;
               margin: 20px 0;">
        📬 Contact Messages
    </h1>
    <table border="1" cellpadding="10" cellspacing="10">
        <tr>
            <th>ID</th>
            <th>Customer ID</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['Contact_id'] ?></td>
            <td><?= $row['Customer_id'] ?? 'Guest' ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['subject']) ?></td>
            <td><?= htmlspecialchars($row['message']) ?></td>
            <td><?= $row['submitted_at'] ?></td>
            <td>
                <a href="?delete=<?= $row['Contact_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this message?')">❌ Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
