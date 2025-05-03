<?php
session_start();
include("connection.php");

// Check if customer is logged in
if (!isset($_SESSION['customer_email'])) {
    echo "<script>alert('Please login to view your cart.'); window.location.href = 'login.php';</script>";
    exit;
}

// Get customer ID
$customer_email = $_SESSION['customer_email'];
$get_customer = $conn->prepare("SELECT Customer_id FROM customer WHERE customer_email = ?");
$get_customer->bind_param("s", $customer_email);
$get_customer->execute();
$result = $get_customer->get_result();
$customer = $result->fetch_assoc();
$customer_id = $customer['Customer_id'];

// Handle Add to Cart
if (isset($_GET['add'])) {
    $book_id = intval($_GET['add']);

    // Check if already in cart
    $check = $conn->prepare("SELECT * FROM cart WHERE Customer_id = ? AND Book_id = ?");
    $check->bind_param("ii", $customer_id, $book_id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows == 0) {
        $insert = $conn->prepare("INSERT INTO cart (Customer_id, Book_id, Quantity) VALUES (?, ?, 1)");
        $insert->bind_param("ii", $customer_id, $book_id);
        $insert->execute();
    }

    header("Location: cart.php");
    exit;
}

// Handle Quantity Increase
if (isset($_GET['inc'])) {
    $book_id = intval($_GET['inc']);
    $update = $conn->prepare("UPDATE cart SET Quantity = Quantity + 1 WHERE Customer_id = ? AND Book_id = ?");
    $update->bind_param("ii", $customer_id, $book_id);
    $update->execute();
    header("Location: cart.php");
    exit;
}

// Handle Quantity Decrease
if (isset($_GET['dec'])) {
    $book_id = intval($_GET['dec']);
    $check_qty = $conn->prepare("SELECT Quantity FROM cart WHERE Customer_id = ? AND Book_id = ?");
    $check_qty->bind_param("ii", $customer_id, $book_id);
    $check_qty->execute();
    $qty_result = $check_qty->get_result()->fetch_assoc();
    if ($qty_result['Quantity'] > 1) {
        $update = $conn->prepare("UPDATE cart SET Quantity = Quantity - 1 WHERE Customer_id = ? AND Book_id = ?");
        $update->bind_param("ii", $customer_id, $book_id);
        $update->execute();
    }
    header("Location: cart.php");
    exit;
}

// Handle Remove from Cart
if (isset($_GET['remove'])) {
    $book_id = intval($_GET['remove']);
    $remove = $conn->prepare("DELETE FROM cart WHERE Customer_id = ? AND Book_id = ?");
    $remove->bind_param("ii", $customer_id, $book_id);
    $remove->execute();
    header("Location: cart.php");
    exit;
}

// Fetch cart items
$cart_items = $conn->prepare("
    SELECT book.Book_id, book.Title, book.Price, book.Image, cart.Quantity 
    FROM cart 
    JOIN book ON cart.Book_id = book.Book_id 
    WHERE cart.Customer_id = ?
");
$cart_items->bind_param("i", $customer_id);
$cart_items->execute();
$result = $cart_items->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Cart</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ecf0f1;
        }

        .cart-container {
            max-width: 1000px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .cart-header {
            background-color: #2C3E50;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 28px;
            letter-spacing: 1px;
        }

        

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            color: #333;
        }

        .cart-table th, .cart-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .cart-table th {
            background-color: #34495E;
            color: #ffffff;
        }

        .cart-table td img {
            width: 70px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }

        .qty-btn {
            padding: 6px 10px;
            margin: 0 5px;
            background-color: #2980B9;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .qty-btn:hover {
            background-color: #1A5276;
        }

        .action-link {
            display: block;
            color: #E74C3C;
            margin: 6px 0;
            text-decoration: none;
            font-weight: bold;
        }

        .action-link:hover {
            text-decoration: underline;
        }

        .total-row {
            background-color: #f4f6f7;
            font-size: 18px;
            font-weight: bold;
        }

        .cart-empty {
            text-align: center;
            padding: 50px;
            font-size: 20px;
            color: #555;
        }

        .place-order-btn {
            display: inline-block;
            margin: 10px auto;
            padding: 12px 30px;
            background-color: #34495E;
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .place-order-btn:hover {
            background-color:rgb(105, 161, 216);
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <div class="cart-header">🛒 My Shopping Cart</div>

        <?php if ($result->num_rows > 0): ?>
            <table class="cart-table" border="1" cellspacing="10" cellpadding="10">
                <tr>
                    <th>Book</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
                <?php 
                $total = 0;
                while ($row = $result->fetch_assoc()): 
                    $subtotal = $row['Price'] * $row['Quantity'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><img src="<?= $row['Image'] ?>" alt="<?= $row['Title'] ?>"></td>
                        <td><?= $row['Title'] ?></td>
                        <td>₹<?= number_format($row['Price'], 2) ?></td>
                        <td>
                            <a class="qty-btn" href="cart.php?dec=<?= $row['Book_id'] ?>">−</a>
                            <?= $row['Quantity'] ?>
                            <a class="qty-btn" href="cart.php?inc=<?= $row['Book_id'] ?>">+</a>
                        </td>
                        <td>₹<?= number_format($subtotal, 2) ?></td>
                        <td>
                            <a class="action-link" href="cart.php?remove=<?= $row['Book_id'] ?>" onclick="return confirm('Remove this item from cart?')">Remove ❌</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <tr class="total-row">
                    <td colspan="4">Total</td>
                    <td colspan="2">₹<?= number_format($total, 2) ?></td>
                </tr>
            </table>

            <div style="text-align:center; padding: 20px;">
                <a href="place_order.php" class="place-order-btn">Proceed to Place Order 🚀</a>
            </div>
        <?php else: ?>
            <div class="cart-empty">Your cart is currently empty. Start shopping now!
            <br/> <br/>
            <a href="browse_books.php" class="place-order-btn"> Please choose books 🚀</a>
            </div>
            
        <?php endif; ?>
    </div>
</body>
</html>
