<?php
session_start();
include("connection.php");

// Fetch filtered books by category if selected
if (isset($_GET['category'])) {
    $selected_category = $_GET['category'];
    $stmt = $conn->prepare("SELECT b.*, a.Author_name, c.Category_name 
                            FROM book b 
                            JOIN author a ON b.Author_id = a.Author_id 
                            JOIN category c ON b.Category_id = c.Category_id 
                            WHERE c.Category_name = ?");
    $stmt->bind_param("s", $selected_category);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $query = "SELECT b.*, a.Author_name, c.Category_name 
              FROM book b 
              JOIN author a ON b.Author_id = a.Author_id 
              JOIN category c ON b.Category_id = c.Category_id";
    $result = $conn->query($query);
}

// Fetch categories for sidebar
$cat_result = $conn->query("SELECT Category_name FROM category ORDER BY Category_name ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Browse Books</title>
    <link rel="stylesheet" href="style.css">
    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            height:auto;
            padding: 20px;
        }

        .category-list {
    width: 220px;
    height:auto;
    background-color:rgb(97, 119, 141);
    border: 2px solid #2C3E50;
    border-radius: 10px;
    padding: 20px 15px;
    font-family: Arial, sans-serif;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.category-list h2 {
    color:rgb(184, 207, 230);
    font-style:italic;
    text-decoration:underline;
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #2C3E50;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.category-list ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-list ul li {
    padding: 10px 10px;
    margin-bottom: 8px;
    border-radius: 6px;
    border: 1px solid #ccc;
    transition: all 0.3s ease;
}

.category-list ul li a {
    text-decoration: none;
    color:rgb(234, 238, 243);
    display: block;
    font-weight: bold;
    font-size: 15px;
    text-decoration: underline;
}

.category-list ul li:hover {
    background-color:rgb(32, 111, 190);
    transform: translateX(5px);
}

.category-list ul li:last-child {
    margin-bottom: 0;
}


.book-list {
    width: 100%;
    height:auto;
    padding: 10px;
    background-color: #f4f6f9;
    border-radius: 10px;
    box-sizing: border-box;
}

.book-header {
    text-align: center;
    font-size: 28px;
    color: #2C3E50;
    font-weight: bold;
    margin-bottom: 30px;
    font-family: 'Segoe UI', sans-serif;
    text-decoration: underline;
}

.book-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 30px;
    padding: 0 10px;
}

.book-card {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s, box-shadow 0.3s;
    overflow: hidden;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.book-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-bottom: 1px solid #eee;
}

.book-card h3 {
    font-size: 18px;
    font-weight: bold;
    margin: 15px 10px 5px;
    color: #2C3E50;
}

.book-card p {
    font-size: 16px;
    font-weight: 600;
    color: #e74c3c;
    margin-bottom: 20px;
}

.book-card a {
    text-decoration: none;
    color: inherit;
    transition: 0.3s;
}

.btn-details {
    background-color: #ffffff;
    color: white;
    padding: 10px;
    text-align: center;
    font-size: 15px;
    font-weight: bold;
    border-top: 1px solid #eee;
    transition: background-color 0.3s;
    text-decoration: none;
}

.btn-details:hover {
    background-color:rgb(118, 156, 194);
}

    </style>
</head>
<body>

<div class="container">
    <!-- Category Sidebar -->
    <div class="category-list">
        <h2>Category</h2>
        <ul>
            <li><a href="browse_books.php">All Books</a></li>
            <?php while ($cat = $cat_result->fetch_assoc()): ?>
                <li><a href="browse_books.php?category=<?= urlencode($cat['Category_name']) ?>">
                    <?= htmlspecialchars($cat['Category_name']) ?>
                </a></li>
            <?php endwhile; ?>
        </ul>
    </div>

    <!-- Book Listing -->
    <div class="book-list">
        <h2 class="book-header">
            <?= isset($_GET['category']) ? htmlspecialchars($_GET['category']) . " Books" : "Browse Books" ?>
        </h2>

        <div class="book-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="book-card">
                <a href="book_details.php?id=<?= $row['Book_id'] ?>" class="btn-details">View Details</a>
                        <img src="<?= htmlspecialchars($row['Image']) ?>" alt="<?= htmlspecialchars($row['Title']) ?>">
                        <h3><?= htmlspecialchars($row['Title']) ?></h3>
                        <p>Rs. <?= $row['Price'] ?></p>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

</body>
</html>
