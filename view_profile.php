<?php
session_start();
include("connection.php");

if (!isset($_SESSION['customer_email'])) {
    header("Location: login.php");
    exit();
}

$customer_email = $_SESSION['customer_email'];
$stmt = $conn->prepare("SELECT * FROM customer WHERE Customer_email = ?");
$stmt->bind_param("s", $customer_email);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            width: 100%;
            background-color: #34495E;
            color: white;
        }
        table th, table td {
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
<div style="text-align:center;">
    <h1 style=" text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: #34495E;
            margin: 20px 0;">My Profile</h1>
    <table border="1" cellpadding="10" cellspacing="10">
        <tr><th>Name</th><td><?= htmlspecialchars($customer['Customer_name']) ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($customer['Customer_email']) ?></td></tr>
        <tr><th>Contact No</th><td><?= htmlspecialchars($customer['Contact_no']) ?></td></tr>
        <tr><th>Address</th><td><?= htmlspecialchars($customer['Address']) ?></td></tr>
        <tr><th>City</th><td><?= htmlspecialchars($customer['City']) ?></td></tr>
        <tr><th>State</th><td><?= htmlspecialchars($customer['State']) ?></td></tr>
    </table>
</div>
</body>
</html>
