<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    exit("Access Denied");
}

// Handle delete review
if (isset($_GET['delete'])) {
    $review_id = intval($_GET['delete']);
    $delete_query = "DELETE FROM review WHERE review_id = $review_id";
    mysqli_query($con, $delete_query);
}

// Fetch reviews with book and customer info
$query = "
    SELECT r.Review_id, r.Review_text, r.Ratings, b.Title AS book_title, c.Customer_name
    FROM review r
    JOIN book b ON r.Book_id = b.Book_id
    JOIN customer c ON r.Customer_id = c.Customer_id
    ORDER BY r.Review_id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Reviews - Admin</title>
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

        .container {
            width: 95%;
            margin: 0 auto;
        }

        

        table td a {
            color: #E74C3C;
            text-decoration: none;
        }

        table td a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style=" text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: #34495E;
            margin: 20px 0;">Customer Reviews</h1>

        <table border="1" cellspacing="10" cellpadding="10">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Book Title</th>
                    <th>Review</th>
                    <th>Rating</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['Review_text']); ?></td>
                            <td><?php echo htmlspecialchars($row['Ratings']); ?></td>
                            <td>
                                <a href="view_reviews.php?delete=<?php echo $row['Review_id']; ?>" onclick="return confirm('Are you sure you want to delete this review?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">No reviews found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
