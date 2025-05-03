<?php
session_start();
include("connection.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit("Invalid Book ID");
}

$book_id = $_GET['id'];

// If admin clicked delete
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_book'])) {
    $stmt = $conn->prepare("DELETE FROM book WHERE Book_id = ?");
    $stmt->bind_param("i", $book_id);
    if ($stmt->execute()) {
        header("Location: display_books.php");
        exit();
    } else {
        echo "Failed to delete the book.";
    }
    $stmt->close();
}

$stmt = $conn->prepare("SELECT b.*, a.Author_name, c.Category_name 
                        FROM book b 
                        JOIN author a ON b.Author_id = a.Author_id 
                        JOIN category c ON b.Category_id = c.Category_id 
                        WHERE b.Book_id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
$stmt->close();

if (!$book) {
    exit("Book not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($book['Title']) ?> - Book Details</title>
    <style>
       body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #ecf0f1;
    margin: 0;
    padding: 0;
}

.book-details-container {
    max-width: 1100px;
    margin: 60px auto;
    background-color: #ffffff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    display: flex;
    gap: 40px;
    align-items: flex-start;
}

.book-image {
    width: 350px;
    height: 500px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

.book-info {
    flex: 1;
}

.book-info p {
    font-size: 18px;
    margin: 10px 0;
    color: #2C3E50;
    line-height: 1.6;
    font-weight: 500;
}

.book-info hr {
    margin: 12px 0;
    border: 0;
    height: 1px;
    background-color: #ccc;
}


.price {
    font-size: 24px;
    color: #e74c3c;
    font-weight: bold;
    margin-top: 10px;
}

.btn {
    display: inline-block;
    margin-top: 25px;
    padding: 12px 24px;
    background-color: #2c3e50;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-size: 16px;
    transition: 0.3s;
}

.btn:hover {
    background-color: #1a252f;
}

.admin-buttons {
    margin-top: 30px;
}

.admin-buttons a,
.admin-buttons form button {
    display: inline-block;
    margin-right: 10px;
    padding: 12px 20px;
    color: #fff;
    border-radius: 6px;
    font-size: 15px;
    border: none;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.admin-buttons .edit {
    background-color: #27ae60;
}

.admin-buttons .delete {
    background-color: #c0392b;
}

.admin-buttons a:hover,
.admin-buttons button:hover {
    opacity: 0.9;
}

    </style>
</head>
<body>

<div class="book-details-container">
    <img class="book-image" src="<?= htmlspecialchars($book['Image']) ?>" alt="<?= htmlspecialchars($book['Title']) ?>">

    <div class="book-info">
        <h2><?= htmlspecialchars($book['Title']) ?></h2>
        <p><strong>Author:</strong> <?= htmlspecialchars($book['Author_name']) ?></p>
<hr>
<p><strong>Category:</strong> <?= htmlspecialchars($book['Category_name']) ?></p>
<hr>
<p class="price">Rs. <?= $book['Price'] ?></p>
<hr>

<?php if (!empty($book['Description'])): ?>
    <p><strong>Description:</strong><br> <?= nl2br(htmlspecialchars($book['Description'])) ?></p>
    <hr>
<?php endif; ?>


        <!-- Customer Add to Cart -->
        <?php if (isset($_SESSION['customer_email'])): ?>
            <a class="btn" href="cart.php?add=<?= $book['Book_id'] ?>">🛒 Add to Cart</a>

        <!-- Admin Edit/Delete -->
        <?php elseif (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
            <div class="admin-buttons">
                <a class="edit" href="update_book.php?id=<?= $book['Book_id'] ?>">✏️ Edit</a>
                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this book?');" style="display:inline;">
                    <button type="submit" name="delete_book" class="delete">❌ Delete</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
