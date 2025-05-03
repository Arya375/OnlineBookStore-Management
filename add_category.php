<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category_name = trim($_POST["category_name"]);

    if (!empty($category_name)) {
        // Normalize input (trim, lowercase for comparison)
        $normalized_name = strtolower($category_name);

        // Check for duplicate category
        $check_stmt = $conn->prepare("SELECT * FROM category WHERE LOWER(TRIM(Category_name)) = ?");
        $check_stmt->bind_param("s", $normalized_name);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error = "Category already exists!";
        } else {
            // Insert only if not found
            $stmt = $conn->prepare("INSERT INTO category (Category_name) VALUES (?)");
            $stmt->bind_param("s", $category_name);

            if ($stmt->execute()) {
                $success = "Category added successfully!";
            } else {
                $error = "Error adding category.";
            }

            $stmt->close();
        }

        $check_stmt->close();
    } else {
        $error = "Category name cannot be empty.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Category</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <h1 style="text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: #34495E;
            margin: 20px 0;">Add New Category</h1>

    <?php if ($success): ?>
        <p class="success-msg"><?= $success ?></p>
    <?php elseif ($error): ?>
        <p class="error-msg"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="category_name">Category Name:</label>
        <input type="text" id="category_name" name="category_name" required>
        <input type="submit" value="Add Category" style="background-color:#2C3E50; color:white; font-weight:bold;">
    </form>
</div>
</body>
</html>