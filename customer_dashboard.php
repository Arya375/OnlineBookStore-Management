<?php
include("header.php");

if (!isset($_SESSION['customer_email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <style>
        .footer {
    text-align: center;
    padding: 10px;
    background-color: #34495E;
    color: white;
}

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background-color: #ECF0F1;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            padding-top: 20px;
            color: white;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .sidebar a {
            padding: 20px;
            text-decoration: none;
            color: white;
            border-bottom: 3px solid #BDC3C7;
            border-right: 3px solid #BDC3C7;
            display: block;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #34495E;
        }

        .content-frame {
            flex-grow: 1;
            border: none;
            min-height: 100vh;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="sidebar">
        <a href="browse_books.php" target="contentFrame">📚 Browse Books</a>
        <a href="cart.php" target="contentFrame">🛒 View Cart</a>
        <a href="place_order.php" target="contentFrame">🧾 Place Order</a>
        <a href="my_orders.php" target="contentFrame">📦 My Orders</a>
        <a href="make_payment.php" target="contentFrame">💳 Make Payment </a>
        <a href="payment_history.php" target="contentFrame">💳 Payment History</a>
        <a href="submit_review.php" target="contentFrame">📝 Submit Review</a>
        <a href="my_reviews.php" target="contentFrame">⭐ My Reviews</a>
        <a href="edit_profile.php" target="contentFrame">👤 Edit Profile</a>
        <a href="view_profile.php" target="contentFrame">👤 My Profile</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- Full height iframe that expands with content -->
    <iframe name="contentFrame" class="content-frame" src="browse_books.php"></iframe>
</div>
 
<?php include("footer.php");?>
</body>
</html>
