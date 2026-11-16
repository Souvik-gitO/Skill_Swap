<?php
session_start();
include "db.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | SkillSwap</title>

    <link rel="stylesheet" href="soubarno_style.css">
</head>

<body>

<div class="form-page">

    <div class="form-card">

        <div class="form-logo">
            <span>S</span> SkillSwap
        </div>

        <p class="form-kicker">
            WELCOME BACK
        </p>

        <h1>
            Pick up where<br>
            you left off.
        </h1>

        <p class="form-intro">
            Sign in to continue your skill-sharing journey.
        </p>

        <?php if ($message) { ?>
            <p style="color:#c0392b;"><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>

        <form action="login.php" method="post">

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

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

            </div>


            <button type="submit" class="form-button">
                Login →
            </button>

        </form>


        <p class="switch-text">
            Don't have an account?
            <a href="register.php">
                Create an account
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