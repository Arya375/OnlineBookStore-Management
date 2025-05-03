<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    exit("Access Denied");
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $author_name = trim($_POST["author_name"]);

    if (!empty($author_name)) {
        // Check if the author already exists
        $check = $conn->prepare("SELECT * FROM author WHERE Author_name = ?");
        $check->bind_param("s", $author_name);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "Author already exists.";
        } else {
            // Proceed to insert
            $stmt = $conn->prepare("INSERT INTO author (Author_name) VALUES (?)");
            $stmt->bind_param("s", $author_name);
            if ($stmt->execute()) {
                $success = "Author added successfully!";
            } else {
                $error = "Error adding author.";
            }
            $stmt->close();
        }

        $check->close();
    } else {
        $error = "Author name cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
<div class="form-container">
    <h1 style="text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: #34495E;
            margin: 20px 0;">Add Author</h1>
    <?php if ($success): ?><p class="success-msg"><?= $success ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error-msg"><?= $error ?></p><?php endif; ?>
    <form method="POST">
        <label for="author_name">Author Name:</label>
        <input type="text" name="author_name" id="author_name" required>
        <input type="submit" value="Add Author" style="background-color:#2C3E50; color:white; font-weight:bold;">
    </form>
</div>
</body>
</html>
