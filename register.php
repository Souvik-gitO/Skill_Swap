<?php
session_start();
include "db.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $skills = trim($_POST['skills']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($name == "" || $email == "" || $password == "") {
        $message = "Please fill in all required fields.";
    } elseif ($password !== $confirm) {
        $message = "Passwords do not match.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, phone, skills, password)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phone, $skills, $hash);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: login.php");
            exit;
        } else {
            $message = "That email is already registered.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account | SkillSwap</title>

    <link rel="stylesheet" href="soubarno_style.css">
</head>

<body>

<div class="form-page">

    <div class="form-card">

        <div class="form-logo">
            <span>S</span> SkillSwap
        </div>

        <p class="form-kicker">
            MAKE YOUR FIRST CONNECTION
        </p>

        <h1>
            Join the<br>
            skill exchange.
        </h1>

        <p class="form-intro">
            Create your campus profile and start exchanging skills.
        </p>


        <?php if ($message) { ?>
            <p style="color:#c0392b;"><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>

        <form action="register.php" method="post">

            <div class="field">

                <label for="name">
                    Full Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter your full name"
                    required
                >

            </div>


            <div class="field">

                <label for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="you@example.com"
                    required
                >

            </div>


            <div class="field">

                <label for="phone">
                    Phone Number
                </label>

                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    placeholder="Enter your phone number"
                    required
                >

            </div>
		
		<div class="field">
		<label for="phone">
                    Skills: 
                </label>

		<input type="text" name="skills"
               placeholder="Enter Skills (e.g. HTML, CSS, PHP)"
               maxlength="255" required>

		</div>

            <div class="field">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="At least 8 characters"
                    minlength="8"
                    required
                >

            </div>


            <div class="field">

                <label for="confirm_password">
                    Confirm Password
                </label>

                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Re-enter your password"
                    minlength="8"
                    required
                >

            </div>


            <button type="submit" class="form-button">
                Create My Account →
            </button>

        </form>


        <p class="switch-text">
            Already have an account?
            <a href="login.php">
                Login
            </a>
        </p>


        <p class="back-home">
            <a href="index.php">
                ← Back to SkillSwap
            </a>
        </p>

    </div>

</div>

</body>
</html>