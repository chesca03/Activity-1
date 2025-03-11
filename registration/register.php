<?php
include 'database.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

  
    if ($password !== $confirm_password) {
        $error = '<i class="bi bi-x-circle-fill text-danger"></i> Passwords do not match!';
    } else {
        // Check if email already exists
        $check_query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = '<i class="bi bi-exclamation-circle-fill text-warning"></i> Email is already registered!';
        } else {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO users (email, password) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ss", $email, $hashed_password); 

            if ($stmt->execute()) {
                $success = '<i class="bi bi-check-circle-fill text-success"></i> Registration successful!';
            } else {
                $error = '<i class="bi bi-x-circle-fill text-danger"></i> Error: ' . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
<div class="container">
    <div class="form-container">
        <h2>Sign Up Now</h2>

        <!-- Error/Success nong Messages -->
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>

            <form action="register.php" method="POST" onsubmit="return validatePasswords();">
            <div class="input-group">
            <i class="bi bi-envelope-fill"></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
            <i class="bi bi-lock-fill"></i>
            <input type="password" name="password" id="password" placeholder="Password" required>
        </div>

        <div class="input-group">
            <i class="bi bi-lock-fill"></i>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
        </div>

    <button type="submit">Register</button>
</form>

    </div>
        <!-- right part design -->
        <div class="welcome-container text-center">
        <img src="ano.png" alt="Welcome Image" class="img-fluid" style="max-width: 100%; height: auto;">
        <h2>Let’s make this official—sign up now!</h2>
        
    </div>

</div>

<script>
    function validatePasswords() {
        var password = document.getElementById("password").value;
        var confirm_password = document.getElementById("confirm_password").value;
        var errorContainer = document.querySelector('.error');

        if (password !== confirm_password) {
            errorContainer.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i> Passwords do not match!';
            return false;
        }
        return true;
    }
</script>

</body>
</html>
