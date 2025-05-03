<?php
include("connection.php");
include("header.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $security_answer = $_POST['security_answer'];

    $sql = "INSERT INTO customer (Customer_name, Customer_email, Customer_password, Contact_no, Address, City, State, Security_answer) 
            VALUES ('$name', '$email', '$password', '$contact', '$address', '$city', '$state', '$security_answer')";

    if (mysqli_query($conn, $sql)) {
        $message = "Registered successfully. <a href='login.php'>Login here</a>";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<div class="registration-container">
<h2>Customer Registration</h2>
<p style="color: green;"><?php echo $message; ?></p>

<form name="registerForm" method="POST" onsubmit="return validateForm();">

    
    <input type="text" name="name" id="name" placeholder="Full Name" onkeyup="validateName()">
    <span id="nameError" class="error"></span><br>

   
    <input type="email" name="email" id="email" placeholder="Email" onkeyup="validateEmail()">
    <span id="emailError" class="error"></span><br>

   
    <input type="password" name="password" id="password" placeholder="Password" onkeyup="validatePassword()">
    <span id="passwordError" class="error"></span><br>

 
    <input type="text" name="contact" id="contact" placeholder="Contact No" onkeyup="validateContact()">
    <span id="contactError" class="error"></span><br>

   
    <textarea name="address" id="address" placeholder="Address" onkeyup="validateAddress()"></textarea>
    <span id="addressError" class="error"></span><br>

   
    <input type="text" name="city" id="city" placeholder="City" onkeyup="validateCity()">
    <span id="cityError" class="error"></span><br>

   
    <input type="text" name="state" id="state" placeholder="State" onkeyup="validateState()">
    <span id="stateError" class="error"></span><br>

    <label for="security_answer">Security Question: What is your favorite book?</label><br>
    <input type="text" name="security_answer" id="security_answer" placeholder="Answer" onkeyup="validateSecurityAnswer()">
    <span id="securityAnswerError" class="error"></span><br>

    <button type="submit">Register</button>
</form>

<style>
    
    .error {
        color: red;
        font-size: 14px;
    }
</style>

<script>
    function validateName() {
    const name = document.getElementById("name").value.trim();
    const error = document.getElementById("nameError");
    if (name.length < 3) {
        error.textContent = "Name should be at least 3 characters.";
        return false;
    } else {
        error.textContent = "";
        return true;
    }
}

function validateEmail() {
    const email = document.getElementById("email").value.trim();
    const error = document.getElementById("emailError");
    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!email.match(emailPattern)) {
        error.textContent = "Please enter a valid email address.";
        return false;
    } else {
        error.textContent = "";
        return true;
    }
}

function validatePassword() {
    const password = document.getElementById("password").value;
    const error = document.getElementById("passwordError");
    const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{6,}$/;

    if (!pattern.test(password)) {
        error.textContent = "Password must be at least 6 characters and include uppercase, lowercase, number, and special character.";
        return false;
    } else {
        error.textContent = "";
        return true;
    }
}

function validateContact() {
    const contact = document.getElementById("contact").value.trim();
    const error = document.getElementById("contactError");
    if (!/^\d{10}$/.test(contact)) {
        error.textContent = "Contact number must be exactly 10 digits.";
        return false;
    } else {
        error.textContent = "";
        return true;
    }
}

function validateAddress() {
    const address = document.getElementById("address").value.trim();
    const error = document.getElementById("addressError");
    if (address === "") {
        error.textContent = "Address cannot be empty.";
        return false;
    } else {
        error.textContent = "";
        return true;
    }
}

function validateCity() {
    const city = document.getElementById("city").value.trim();
    const error = document.getElementById("cityError");
    if (city === "") {
        error.textContent = "City cannot be empty.";
        return false;
    } else {
        error.textContent = "";
        return true;
    }
}

function validateState() {
    const state = document.getElementById("state").value.trim();
    const error = document.getElementById("stateError");
    if (state === "") {
        error.textContent = "State cannot be empty.";
        return false;
    } else {
        error.textContent = "";
        return true;
    }
}

function validateSecurityAnswer() {
    const answer = document.getElementById("security_answer").value.trim();
    const error = document.getElementById("securityAnswerError");
    if (answer === "") {
        error.textContent = "Answer to the security question is required.";
        return false;
    } else {
        error.textContent = "";
        return true;
    }
}

function validateForm() {
    const valid =
        validateName() &
        validateEmail() &
        validatePassword() &
        validateContact() &
        validateAddress() &
        validateCity() &
        validateState() &
        validateSecurityAnswer();

    return !!valid;
}
</script>

</div>

<?php include("footer.php"); ?>
