<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    exit("Access Denied");
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $author_id = $_POST["author_id"];
    $author_name = trim($_POST["author_name"]);
    if (!empty($author_name)) {
        $stmt = $conn->prepare("UPDATE author SET Author_name=? WHERE Author_id=?");
        $stmt->bind_param("si", $author_name, $author_id);
        if ($stmt->execute()) {
            $success = "✅ Author updated successfully!";
        } else {
            $error = "❌ Error updating author.";
        }
        $stmt->close();
    } else {
        $error = "❌ Author name cannot be empty.";
    }
}

$authors = $conn->query("SELECT * FROM author");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Author</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 500px;
            margin: 60px auto;
            background-color: #ecf0f1;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }


        .form-container select,
        .form-container input[type="text"],
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
            text-align: center;
            font-weight: bold;
        }

        .error-msg {
            color: red;
            text-align: center;
            font-weight: bold;
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
            margin: 20px 0;">✏️ Update Author</h1>

    <?php if ($success): ?><p class="success-msg"><?= $success ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error-msg"><?= $error ?></p><?php endif; ?>

    <form method="POST">
        <label for="author_id">Select Author:</label>
        <select name="author_id" id="author_id" required>
            <option value="">-- Select Author --</option>
            <?php while ($row = $authors->fetch_assoc()): ?>
                <option value="<?= $row['Author_id'] ?>"><?= htmlspecialchars($row['Author_name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="author_name">New Author Name:</label>
        <input type="text" name="author_name" id="author_name" placeholder="Enter new author name" required>

        <input type="submit" value="Update Author">
    </form>
</div>

</body>
</html>
