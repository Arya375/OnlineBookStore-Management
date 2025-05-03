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
    $stmt = $conn->prepare("SELECT o.Order_id, o.Customer_id, o.Order_date, o.Order_status, c.Customer_name 
                            FROM `order` o
                            JOIN customer c ON o.Customer_id = c.Customer_id
                            WHERE o.Order_id LIKE ? OR c.Customer_name LIKE ?
                            ORDER BY o.Order_date DESC");
    $search_term = "%$search_query%";
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Fetch all orders
    $result = $conn->query("SELECT o.Order_id, o.Customer_id, o.Order_date, o.Order_status, c.Customer_name 
                            FROM `order` o
                            JOIN customer c ON o.Customer_id = c.Customer_id
                            ORDER BY o.Order_date DESC");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Orders</title>
    <style>
.container {
            width: 95%;
            margin: 0 auto;
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
        input[type="text"] {
            width:250px;
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
    </style>
</head>
<body>
    <div class="container">
    <h1 style="text-align: center;
            font-style: italic;
            text-decoration: underline;
            color: #34495E;
            margin: 20px 0;">Search Orders</h2>
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by Order ID or Customer Name" value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit">Search</button>
    </form>

    <table border="1" cellspacing="10" cellpadding="10">
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Order Date</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['Order_id']; ?></td>
            <td><?php echo htmlspecialchars($row['Customer_name']); ?></td>
            <td><?php echo $row['Order_date']; ?></td>
            <td><?php echo $row['Order_status']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
        </div>
</body>
</html>
