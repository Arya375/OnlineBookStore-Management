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

// Handle deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $review_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM review WHERE Review_id = ? AND Customer_id = ?");
    $stmt->bind_param("ii", $review_id, $customer_id);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT r.*, b.Title FROM review r 
                        JOIN book b ON r.Book_id = b.Book_id 
                        WHERE r.Customer_id = $customer_id 
                        ORDER BY r.Review_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <style>
         body {
            margin: 0;
            background-color: #BDC3C7;
        }

        .container {
            width: 95%;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: #34495E;
            margin: 20px 0;
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
    <h1>My Reviews</h1>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="10" cellspacing="10">
            <tr>
                <th>Book Title</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Title']) ?></td>
                    <td><?= $row['Ratings'] ?>/5</td>
                    <td><?= htmlspecialchars($row['Review_text']) ?></td>
                    <td><?= $row['Review_date'] ?></td>
                    <td>
                        <a href="?delete=<?= $row['Review_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this review?')">❌ Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="color:red;">You have not submitted any reviews yet.</p>
    <?php endif; ?>
</div>
</body>
</html>
