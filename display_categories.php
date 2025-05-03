<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Handle delete
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $stmt = $conn->prepare("DELETE FROM category WHERE Category_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT * FROM category");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Categories</title>
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
            margin: 20px 0;">All Categories</h1>

    <table border="1" cellpadding="10" cellspacing="10">
        <tr>
            <th>Category ID</th>
            <th>Category Name</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['Category_id'] ?></td>
            <td><?= htmlspecialchars($row['Category_name']) ?></td>   
            <td>
    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this Category?');" style="display:inline;">
        <input type="hidden" name="delete_id" value="<?= $row['Category_id'] ?>">
        <button type="submit" class="delete-btn" style="background:none;border:none;cursor:pointer;">❌ Delete</button>
    </form>
</td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
