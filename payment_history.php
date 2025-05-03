<?php
session_start();
include("connection.php");

// Check if the customer is logged in
if (!isset($_SESSION["customer_email"])) {
    header("Location: login.php");
    exit();
}

// Retrieve the logged-in customer's email from the session
$customer_email = $_SESSION["customer_email"];

// Prepare and execute the query to fetch the Customer_id
$stmt = $conn->prepare("SELECT Customer_id FROM customer WHERE Customer_email = ?");
$stmt->bind_param("s", $customer_email);
$stmt->execute();
$result = $stmt->get_result();

// Check if the customer exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $customer_id = $row['Customer_id'];

    // Prepare and execute the query to fetch payment records
    $stmt = $conn->prepare("SELECT * FROM payment WHERE Customer_id = ? ORDER BY Payment_id DESC");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $payments = $stmt->get_result();
} else {
    // Handle the case where no customer is found
    echo "Customer not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Payment History</title>
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

        table {
            width: 100%;
            background-color: #34495E;
            color: white;
        }
        table td, table th {
            padding: 10px;
            text-align: center;
        }

        
    </style>
</head>
<body>
<div class="container">
    <h1>My Payment History</h1>
    <table border="1" cellspacing="10" cellpadding="10">
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Order ID</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($payments->num_rows > 0): ?>
                <?php while ($row = $payments->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Payment_id']) ?></td>
                        <td><?= htmlspecialchars($row['Order_id']) ?></td>
                        <td><?= htmlspecialchars($row['Amount']) ?></td>
                        <td><?= htmlspecialchars($row['Payment_method']) ?></td>
                        <td><?= htmlspecialchars($row['Payment_status']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No payment history found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
