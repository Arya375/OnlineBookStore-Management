<?php
include("connection.php");
include("header.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM customer WHERE Customer_email='$email' AND Customer_password='$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $_SESSION["customer_email"] = $email;
        $_SESSION["just_logged_in"] = true;
        header("Location: customer_dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>


<div class="form-container">
    <h2>Customer Login</h2>

    <?php if (!empty($error)) { ?>
        <p class="error-msg"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST" onsubmit="return validateLogin();">
        <input type="email" name="email" id="loginEmail" placeholder="Email"><br>
        <span id="loginEmailError" class="error"></span><br>

        <input type="password" name="password" id="loginPassword" placeholder="Password"><br>
        <span id="loginPasswordError" class="error"></span><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
    <p> <a href="forgot_password.php">Forgot your Password?</a></p>

    

</div>

<script>
function validateLogin() {
    let valid = true;

    const email = document.getElementById("loginEmail").value.trim();
    const password = document.getElementById("loginPassword").value.trim();

    if (!email) {
        document.getElementById("loginEmailError").textContent = "Email is required.";
        valid = false;
    } else {
        document.getElementById("loginEmailError").textContent = "";
    }

    if (!password) {
        document.getElementById("loginPasswordError").textContent = "Password is required.";
        valid = false;
    } else {
        document.getElementById("loginPasswordError").textContent = "";
    }

    return valid;
}
</script>



<?php include("footer.php"); ?>
