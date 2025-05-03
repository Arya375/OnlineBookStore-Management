<?php
include("connection.php");
include("header.php");

$success = $error = "";
$email = $subject = $message = "";
$customer_id = null;

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (isset($_SESSION["customer_email"])) {
    $customer_email = $_SESSION["customer_email"];

    // Get customer ID from email
    $stmt = $conn->prepare("SELECT Customer_id FROM customer WHERE Customer_email = ?");
    $stmt->bind_param("s", $customer_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $customer_id = $row['Customer_id'];
        $email = $customer_email; // Prefill email for logged-in users
    } else {
        $error = "Customer not found.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    if (!empty($email) && !empty($subject) && !empty($message)) {
        if ($customer_id !== null) {
            // Insert with Customer_id if user is logged in
            $stmt = $conn->prepare("INSERT INTO contact (Customer_id, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $customer_id, $email, $subject, $message);
        } else {
            // Insert without Customer_id if user is not logged in
            $stmt = $conn->prepare("INSERT INTO contact (email, subject, message) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $subject, $message);
        }

        if ($stmt->execute()) {
            $success = "Your message has been sent!";
        } else {
            $error = "Execute failed: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error = "All fields are required.";
    }
}
?>

<div class="form-container">
    <h2>Contact Us</h2>

    <?php if ($success): ?><p class="success-msg"><?= $success ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error-msg"><?= $error ?></p><?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Your Email" value="<?php echo htmlspecialchars($email); ?>" required>
        <input type="text" name="subject" placeholder="Subject" value="<?php echo htmlspecialchars($subject); ?>" required>
        <textarea name="message" placeholder="Your Message..." rows="5" required><?php echo htmlspecialchars($message); ?></textarea>
        <button type="submit">Send Message</button>
    </form>
</div>