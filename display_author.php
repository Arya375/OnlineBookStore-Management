<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    exit("Access Denied");
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $author_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM author WHERE Author_id = ?");
    $stmt->bind_param("i", $author_id);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT * FROM author");
?>

<!DOCTYPE html>
<html>
<head>
    
    <style>
         body{
            margin:0px;
            background-color: #BDC3C7;
        }

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
            margin: 20px 0;">All Authors</h1>
    <table border="1" cellpadding="10" cellspacing="10">
        <tr>
            <th>Author ID</th>
            <th>Author Name</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['Author_id'] ?></td>
            <td><?= htmlspecialchars($row['Author_name']) ?></td>
            <td><a href="?delete=<?= $row['Author_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this author?')">❌ Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
