<?php
include("connection.php");
include("header.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $security_answer = trim($_POST["security_answer"]);
    $new_password = trim($_POST["new_password"]);

    if (!empty($email) && !empty($security_answer) && !empty($new_password)) {
        // Validate user with email and security answer
        $stmt = $conn->prepare("SELECT * FROM customer WHERE Customer_email = ? AND Security_answer = ?");
        $stmt->bind_param("ss", $email, $security_answer);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update password
            $update = $conn->prepare("UPDATE customer SET Customer_password = ? WHERE Customer_email = ?");
            $update->bind_param("ss", $new_password, $email);
            if ($update->execute()) {
                $message = "<span style='color:green;'>Password updated successfully. <a href='login.php'>Login here</a></span>";
            } else {
                $message = "<span style='color:red;'>Error updating password.</span>";
            }
            $update->close();
        } else {
            $message = "<span style='color:red;'>Incorrect email or security answer.</span>";
        }

        $stmt->close();
    } else {
        $message = "<span style='color:red;'>All fields are required.</span>";
    }
}
?>

<div class="form-container">
    <h2>Forgot Password</h2>
    <p><?= $message ?></p>
    <form method="POST">
        <label for="email">Registered Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label for="security_answer">What is your favorite book?</label><br>
        <input type="text" name="security_answer" required><br><br>

        <label for="new_password">New Password:</label><br>
        <input type="password" name="new_password" required><br><br>

        <input type="submit" value="Reset Password" style="background-color:#2C3E50; color:white; font-weight:bold;">
    </form>
</div>

<?php include("footer.php"); ?>
