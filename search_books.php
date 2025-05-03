<?php
session_start();
include("connection.php");

// Function to handle book search
if (isset($_POST['search'])) {
    // Sanitize the inputs to prevent SQL injection
    $search_term = mysqli_real_escape_string($conn, $_POST['search_term']);
    $search_category = mysqli_real_escape_string($conn, $_POST['search_category']);

    // SQL query to search for books based on title, author, or category
    $query = "SELECT b.Book_id, b.Title, a.Author_name, c.Category_name, b.Price, b.Stock_quantity 
              FROM book b
              JOIN author a ON b.Author_id = a.Author_id
              JOIN category c ON b.Category_id = c.Category_id
              WHERE (b.Title LIKE '%$search_term%' 
              OR a.Author_name LIKE '%$search_term%')";

    // Add category condition if it's selected
    if (!empty($search_category)) {
        $query .= " AND c.Category_name = '$search_category'";
    }


    $result = mysqli_query($conn, $query);
} else {
    // Default query when no search is performed
    $query = "SELECT b.Book_id, b.Title, a.Author_name, c.Category_name, b.Price, b.Stock_quantity 
              FROM book b
              JOIN author a ON b.Author_id = a.Author_id
              JOIN category c ON b.Category_id = c.Category_id";
    $result = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books - Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            background-color: #BDC3C7;
        }

        .container {
            width: 95%;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: #34495E;
            margin: 20px 0;
        }

        form {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        input[type="text"], select, button {
            padding: 10px;
            margin: 5px;
            font-size: 14px;
            width: 200px;
        }

        button {
            background-color: #34495E;
            color: white;
            border: none;
            cursor: pointer;
        }

        body{
            margin:0px;
            background-color: #BDC3C7;
        }

        table {
            width: 100%;
            background-color: #34495E;
            color: white;
        }
        table td, table th {
            padding: 10px;
            text-align: center;
        }

        table td a {
            color: #E74C3C;
            text-decoration: none;
        }

        table td a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Search Books</h1>

        <!-- Search Form -->
        <form action="search_books.php" method="POST">
            <input type="text" name="search_term" placeholder="Search by Title or Author" value="<?php echo isset($search_term) ? $search_term : ''; ?>">
            <select name="search_category">
                <option value="">Search by Category</option>
                <?php
                // Fetch all categories from the database for the dropdown list
                $category_query = "SELECT Category_name FROM category";
                $category_result = mysqli_query($conn, $category_query);
                while ($category_row = mysqli_fetch_assoc($category_result)) {
                    echo "<option value=\"" . $category_row['Category_name'] . "\" " . ($search_category == $category_row['Category_name'] ? "selected" : "") . ">" . $category_row['Category_name'] . "</option>";
                }
                ?>
            </select>
            <button type="submit" name="search">Search</button>
        </form>

        <!-- Display Results -->
        <table border="1" cellspacing="10" cellpadding="10">
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Title']); ?></td>
                        <td><?php echo htmlspecialchars($row['Author_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Category_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Price']); ?></td>
                        <td><?php echo htmlspecialchars($row['Stock_quantity']); ?></td>
                        <td>
                            <a href="update_book.php?Book_id=<?php echo $row['Book_id']; ?>">Edit</a> | 
                            <a href="delete_book.php?Book_id=<?php echo $row['Book_id']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
