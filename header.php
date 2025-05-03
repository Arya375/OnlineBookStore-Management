<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bookstore Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .header {
            text-align: center;
            font-size: 40px;
            background-color: #34495E;
            font-style: italic;
            border-bottom: 3px solid lightgray; 
            color: white;
            padding: 20px 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #34495E;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            border-bottom: 3px solid lightgray;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 12px;
            border-right: 2px solid lightgray;
        }

        .nav-links a:last-child {
            border-right: none;
        }

     
        .banner-image {
    width: 100%;
    max-height: 500px;
    overflow: hidden;
}

.banner-image img {
    width: 100%;
    height: auto;
    display: block;
    object-fit: cover;
}


      </style>
</head>
<body>

<div class="header">📚 Online Bookstore</div>

<div class="navbar">
    <div class="nav-links">
        <a href="index.php">Home</a>
        <?php if (isset($_SESSION['customer_email'])): ?>
            <a href="customer_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="admin_login.php">Admin Login</a> <!-- ✅ Added Admin Login -->
        <?php endif; ?>
            

        <a href="contact.php">Contact Us</a>
    </div>
    <div class="search-section">
    <form action="browse_books.php" method="GET">
        <input type="text" name="query" placeholder="Search by author, title, category...">
        <button type="submit">🔍 Search</button>
    </form>
</div>
</div>




</body>
</html>  