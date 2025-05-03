<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    exit("Access Denied");
}

$success = $error = "";

// Fetch authors and categories for dropdown
$authors = $conn->query("SELECT Author_id, Author_name FROM author");
$categories = $conn->query("SELECT Category_id, Category_name FROM category");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = trim($_POST['description']);
    $image = trim($_POST['image']);

    if ($title && $author_id && $category_id && $price && $stock) {
        $stmt = $conn->prepare("INSERT INTO book (Title, Author_id, Category_id, Price, Stock_quantity, Description, Image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiisss", $title, $author_id, $category_id, $price, $stock, $description, $image);

        if ($stmt->execute()) {
            $success = "✅ Book added successfully!";
        } else {
            $error = "❌ Failed to add book.";
        }
        $stmt->close();
    } else {
        $error = "❗ All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 20px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        
        label {
            display: block;
            margin-top: 15px;
            color: #2C3E50;
            font-weight: bold;
        }
        input[type="text"], input[type="number"], select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #bbb;
            border-radius: 6px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
        }
        input[type="submit"] {
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            background-color: #2C3E50;
            color: white;
            border: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #1a252f;
        }
        .success-msg {
            color: green;
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .error-msg {
            color: red;
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h1 style="text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: #34495E;
            margin: 20px 0;">📖 Add New Book</h1>

    <?php if ($success): ?>
        <p class="success-msg"><?= $success ?></p>
    <?php elseif ($error): ?>
        <p class="error-msg"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" required>

        <label>Author:</label>
        <select name="author_id" required>
            <option value="">-- Select Author --</option>
            <?php while ($row = $authors->fetch_assoc()): ?>
                <option value="<?= $row['Author_id'] ?>"><?= htmlspecialchars($row['Author_name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Category:</label>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php while ($row = $categories->fetch_assoc()): ?>
                <option value="<?= $row['Category_id'] ?>"><?= htmlspecialchars($row['Category_name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Price (₹):</label>
        <input type="number" name="price" step="0.01" required>

        <label>Stock Quantity:</label>
        <input type="number" name="stock" required>

        <label>Description:</label>
        <textarea name="description" rows="2"></textarea>

        <label>Image Path (e.g. <code>book images/book1.jpg</code>):</label>
        <input type="text" name="image" placeholder="Relative path to image (optional)">

        <input type="submit" value="Add Book">
    </form>
</div>
</body>
</html>
