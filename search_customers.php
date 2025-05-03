<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    exit("Access Denied");
}

// Search functionality
$search_query = "";
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM customer WHERE Customer_name LIKE ? OR Customer_email LIKE ?");
    $search_term = "%$search_query%";
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Fetch all customers
    $result = $conn->query("SELECT * FROM customer ORDER BY Customer_name");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Customers</title>
    <style>
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
        input[type="text"] {
            padding: 6px;
            margin: 10px;
        }
        button {
            padding: 6px 12px;
            background-color: #2C3E50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .container {
            width: 95%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
    <h1 style="text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: #34495E;
            margin: 20px 0;">Search Customers</h1>
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by Customer Name or Email" value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit">Search</button>
    </form>

    <table border="1" cellspacing="10" cellpadding="10">
        <tr>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Contact</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['Customer_id']; ?></td>
            <td><?php echo htmlspecialchars($row['Customer_name']); ?></td>
            <td><?php echo htmlspecialchars($row['Customer_email']); ?></td>
            <td><?php echo htmlspecialchars($row['Contact_no']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
        </div>
</body>
</html>
