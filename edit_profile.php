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

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);

    // Server-side validation
    if ($address && preg_match("/^[a-zA-Z\s]+$/", $city) && preg_match("/^[a-zA-Z\s]+$/", $state)) {
        $stmt = $conn->prepare("UPDATE customer SET Address=?, City=?, State=? WHERE Customer_id=?");
        $stmt->bind_param("sssi", $address, $city, $state, $customer['Customer_id']);
        if ($stmt->execute()) {
            $success = "✅ Profile updated successfully!";
        } else {
            $error = "❌ Failed to update profile.";
        }
        $stmt->close();
    } else {
        $error = "⚠️ Please enter valid city and state names (letters only).";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        body {
            margin: 0;
            background-color: #ECF0F1;
            font-family: 'Segoe UI', sans-serif;
        }

        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px 40px;
            background-color: #34495E;
            color: white;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.3);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #fff;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: none;
            border-radius: 5px;
            background-color: #ECF0F1;
            color: #2C3E50;
            font-size: 16px;
        }

        input[readonly] {
            background-color: #d5d8dc;
            cursor: not-allowed;
        }

        textarea {
            resize: vertical;
            height: 80px;
        }

        input[type="submit"] {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: #2C3E50;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #1A242F;
        }

        .success-msg {
            background-color: #2ECC71;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
        }

        .error-msg {
            background-color: #E74C3C;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>✏️ Edit Profile</h2>

    <?php if ($success): ?>
        <div class="success-msg"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="error-msg"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($customer['Customer_email']) ?>" readonly>

        <label>Password:</label>
        <input type="text" name="password" value="<?= htmlspecialchars($customer['Customer_password']) ?>" readonly>

        <label>Address:</label>
        <textarea name="address" required><?= htmlspecialchars($customer['Address']) ?></textarea>

        <label>City:</label>
        <input type="text" name="city" value="<?= htmlspecialchars($customer['City']) ?>" required pattern="[A-Za-z\s]+" title="Only letters allowed">

        <label>State:</label>
        <input type="text" name="state" value="<?= htmlspecialchars($customer['State']) ?>" required pattern="[A-Za-z\s]+" title="Only letters allowed">

        <input type="submit" value="Update Profile">
    </form>
</div>

</body>
</html>
