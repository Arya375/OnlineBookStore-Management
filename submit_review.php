<?php
session_start();
include("connection.php");

if (!isset($_SESSION["customer_email"])) {
    header("Location: login.php");
    exit();
}

$success = $error = "";
$customer_email = $_SESSION["customer_email"];
$result = $conn->query("SELECT Customer_id FROM customer WHERE Customer_email = '$customer_email'");
$row = $result->fetch_assoc();
$customer_id = $row['Customer_id'];

// Get purchased books (from orders)
$books = $conn->query("
    SELECT DISTINCT b.Book_id, b.Title 
    FROM book b
    INNER JOIN order_detail od ON b.Book_id = od.Book_id
    INNER JOIN `order` o ON od.Order_id = o.Order_id
    WHERE o.Customer_id = $customer_id
");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $book_id = $_POST["book_id"];
    $ratings = $_POST["ratings"];
    $review_text = trim($_POST["review_text"]);

    if (!empty($book_id) && !empty($review_text) && !empty($ratings)) {
        $stmt = $conn->prepare("INSERT INTO review (Book_id, Customer_id, Ratings, Review_text) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $book_id, $customer_id, $ratings, $review_text);
        if ($stmt->execute()) {
            $success = "Review submitted successfully!";
        } else {
            $error = "Failed to submit review.";
        }
        $stmt->close();
    } else {
        $error = "Please select a book, provide a rating, and enter a review.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Submit Book Review</title>
    <link rel="stylesheet" href="style.css">
    <style>
         body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            width: 100%;
            max-width: 700px;
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #2C3E50;
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin-top: 20px;
            color: #34495E;
            font-weight: bold;
        }
        select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
            font-size: 16px;
        }
        .star-rating {
            display: flex;
            justify-content: center;
            flex-direction: row-reverse;
            margin-top: 10px;
        }
        .star-rating input[type="radio"] {
            display: none;
        }
        .star-rating label {
            font-size: 2em;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s;
            margin: 0 5px; /* Adjusts spacing between stars */
        }
        .star-rating input[type="radio"]:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #f5b301;
        }
        .submit-button {
            margin-top: 30px;
            background-color: #2C3E50;
            color: white;
            font-weight: bold;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .submit-button:hover {
            background-color: #1a252f;
        }
        .success-msg {
            color: #4CAF50;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .error-msg {
            color: #E74C3C;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Submit Book Review</h2>
    <?php if ($success): ?>
        <p class="success-msg"><?= $success ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="error-msg"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="book_id">Select Book:</label>
        <select name="book_id" id="book_id" required>
            <option value="">-- Select --</option>
            <?php while ($b = $books->fetch_assoc()): ?>
                <option value="<?= $b['Book_id'] ?>"><?= htmlspecialchars($b['Title']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="ratings">Rating:</label>
        <div class="star-rating">
            <input type="radio" id="star5" name="ratings" value="5" required />
            <label for="star5" title="5 stars">★</label>
            <input type="radio" id="star4" name="ratings" value="4" />
            <label for="star4" title="4 stars">★</label>
            <input type="radio" id="star3" name="ratings" value="3" />
            <label for="star3" title="3 stars">★</label>
            <input type="radio" id="star2" name="ratings" value="2" />
            <label for="star2" title="2 stars">★</label>
            <input type="radio" id="star1" name="ratings" value="1" />
            <label for="star1" title="1 star">★</label>
        </div>

        <label for="review_text">Review:</label>
        <textarea name="review_text" id="review_text" rows="5" required></textarea>

        <button type="submit" class="submit-button">Submit Review</button>
    </form>
</div>
</body>
</html>
