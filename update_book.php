<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$success = $error = "";

if (isset($_POST['update'])) {
    $book_id = $_POST['book_id'];
    $title = trim($_POST['title']);
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE book SET Title=?, Author_id=?, Category_id=?, Price=? WHERE Book_id=?");
    $stmt->bind_param("siiii", $title, $author_id, $category_id, $price, $book_id);

    if ($stmt->execute()) {
        $success = "✅ Book updated successfully!";
    } else {
        $error = "❌ Failed to update book.";
    }
    $stmt->close();
}

$books = $conn->query("SELECT * FROM book");
$authors = $conn->query("SELECT * FROM author");
$categories = $conn->query("SELECT * FROM category");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Book</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 550px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ecf0f1;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }


        .form-container select,
        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
        }

        .form-container input[type="submit"] {
            background-color: #2C3E50;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .form-container input[type="submit"]:hover {
            background-color: #1a242f;
        }

        .success-msg {
            color: green;
            font-weight: bold;
            text-align: center;
        }

        .error-msg {
            color: red;
            font-weight: bold;
            text-align: center;
        }

        label {
            font-weight: bold;
            color: #2C3E50;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h1 style="text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: #34495E;
            margin: 20px 0;">✏️ Update Book</h1>

    <?php if ($success): ?><p class="success-msg"><?= $success ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error-msg"><?= $error ?></p><?php endif; ?>

    <form method="POST">
        <label for="book_id">Select Book:</label>
        <select name="book_id" required>
            <option value="">-- Select Book --</option>
            <?php while ($row = $books->fetch_assoc()): ?>
                <option value="<?= $row['Book_id'] ?>"><?= htmlspecialchars($row['Title']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="title">New Title:</label>
        <input type="text" name="title" id="title" required>

        <label for="author_id">Author:</label>
        <select name="author_id" id="author_id" required>
            <option value="">-- Select Author --</option>
            <?php while ($row = $authors->fetch_assoc()): ?>
                <option value="<?= $row['Author_id'] ?>"><?= htmlspecialchars($row['Author_name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" required>
            <option value="">-- Select Category --</option>
            <?php while ($row = $categories->fetch_assoc()): ?>
                <option value="<?= $row['Category_id'] ?>"><?= htmlspecialchars($row['Category_name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="price">Price (₹):</label>
        <input type="number" name="price" id="price" min="1" required>

        <input type="submit" name="update" value="Update Book">
    </form>
</div>

</body>
</html>
