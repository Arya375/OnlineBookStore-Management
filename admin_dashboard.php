<?php

session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
   
    <style>

.footer {
    text-align: center;
    padding: 10px;
    background-color: #34495E;
    color: white;
}


        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .dashboard-container {
            display: flex;
            height: 100%;
        }

        .sidebar {
            width: 300px;
            background-color: #2C3E50;
            padding: 20px;
            color: white;
            box-sizing: border-box;
        }

        .sidebar h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .menu-section {
            margin-bottom: 20px;
        }

        .menu-section label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .menu-section select {
            width: 100%;
            padding: 8px;
            font-size: 14px;
        }

        .logout-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #afa4f2;
            text-decoration: none;
            font-weight: bold;
        }

        .logout-link:hover {
            text-decoration: underline;
        }

        .content-frame {
            flex-grow: 1;
            border: none;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h1>Admin Panel</h1>

        <!-- Author Management -->
        <div class="menu-section">
            <label for="author-actions">Manage Authors:</label>
            <select id="author-actions" onchange="loadPage(this)">
                <option value="">-- Select Action --</option>
                <option value="add_author.php">➕ Add Author</option>
                <option value="update_author.php">✏️ Update Author</option>
                <option value="display_author.php">📄 View/Delete Authors</option>
            </select>
        </div>

        

        <!-- Categories -->
        <div class="menu-section">
            <label for="category-actions">Manage Categories:</label>
            <select id="category-actions" onchange="loadPage(this)">
                <option value="">-- Select Action --</option>
                <option value="add_category.php">➕ Add Category</option>
                <option value="update_category.php">✏️ Update Category</option>
                <option value="display_categories.php">📄 View/delete Categories</option>
            </select>
        </div>


        <!-- Book Management -->
        <div class="menu-section">
            <label for="book-actions">Manage Books:</label>
            <select id="book-actions" onchange="loadPage(this)">
                <option value="">-- Select Action --</option>
                <option value="add_book.php">➕ Add Book</option>
                <option value="update_book.php">✏️ Update Book</option>
                <option value="display_books.php">📄 View/delete Books</option>
                <option value="search_books.php">🔍 Search Books</option>
            </select>
        </div>

        <!-- Orders -->
        <div class="menu-section">
            <label for="order-actions">Manage Orders:</label>
            <select id="order-actions" onchange="loadPage(this)">
                <option value="">-- Select Action --</option>
                <option value="view_orders.php">📦 View/Delete Orders</option>
                <option value="update_order_status.php">🚚 Update Status</option>
                <option value="search_orders.php"> 🔍 Search Orders</option>
            </select>
        </div>

        <!-- Customers --> 
        <div class="menu-section">
            <label for="customer-actions">Manage Customers:</label>
            <select id="customer-actions" onchange="loadPage(this)">
                <option value="">-- Select Action --</option>
                <option value="display_customers.php">👤 View/Delete Customers</option>
                <option value="search_customers.php">🔍 Search Customers</option>
            </select>
        </div>

        <!-- Payments -->
        <div class="menu-section">
            <label for="payment-actions">Manage Payments:</label>
            <select id="payment-actions" onchange="loadPage(this)">
                <option value="">-- Select Action --</option>
                <option value="view_payments.php">💰 View/Delete Payments</option>
        
            </select>
        </div>

        <!-- Reviews -->
        <div class="menu-section">
            <label for="review-actions">Manage Reviews:</label>
            <select id="review-actions" onchange="loadPage(this)">
                <option value="">-- Select Action --</option>
                <option value="view_reviews.php">⭐ View/Delete Reviews</option>
            
            </select>
    </div>
    <div class="menu-section">
            <label for="review-actions">Manage Messages:</label>
            <select onchange="loadPage(this)">
            <option value="">-- Select Action --</option>
            <option value="view_contacts.php">📬 View/Delete Messages</option>
    </select>
        </div>

        <a href="logout.php" class="logout-link">🔒 Logout</a>
    </div>

    <!-- Content Frame -->
    <iframe name="content-frame" class="content-frame"></iframe>
</div>

<script>
function loadPage(select) {
    const value = select.value;
    if (value) {
        document.querySelector("iframe").src = value;
    }
}
</script>
<?php include("footer.php"); ?>
</body>
</html>
