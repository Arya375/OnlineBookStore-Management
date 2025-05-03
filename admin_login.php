<?php

include("connection.php");
include("header.php");
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM admin WHERE Admin_email='$email' AND Admin_password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $_SESSION["admin_logged_in"] = true;
        $_SESSION["admin_email"] = $email;

        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}

?>

<div class="form-container">
    <h2>Admin Login</h2>

    <?php if (!empty($error)) { ?>
        <p class="error-msg"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST" onsubmit="return validateAdminLogin();">
        <input type="email" name="email" id="adminEmail" placeholder="Admin Email"><br>
        <span id="adminEmailError" class="error"></span><br>

        <input type="password" name="password" id="adminPassword" placeholder="Password"><br>
        <span id="adminPasswordError" class="error"></span><br>

        <button type="submit">Login</button>
    </form>
</div>

<script>
function validateAdminLogin() {
    let valid = true;
    const email = document.getElementById("adminEmail").value.trim();
    const password = document.getElementById("adminPassword").value.trim();

    if (!email) {
        document.getElementById("adminEmailError").textContent = "Email is required.";
        valid = false;
    } else {
        document.getElementById("adminEmailError").textContent = "";
    }

    if (!password) {
        document.getElementById("adminPasswordError").textContent = "Password is required.";
        valid = false;
    } else {
        document.getElementById("adminPasswordError").textContent = "";
    }

    return valid;
}
</script>

<?php include("footer.php"); ?>
