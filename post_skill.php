
<?php
session_start();
include "db.php";

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if (isset($_POST['post_skill'])) {

    $teach = trim($_POST['teach']);
    $learn = trim($_POST['learn']);
    $description = trim($_POST['description']);

    $userId = $_SESSION['user_id'];

    if ($teach == "" || $learn == "") {

        $message = "Please enter both skills.";

    } else {

        $sql = "INSERT INTO skills
                (user_id, skill_to_teach, skill_to_learn, description)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt, "isss",
            $userId, $teach, $learn, $description
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Could not post the skill.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Post Skill Swap</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .swap-symbol {
    display: block;
    width: 100%;
    text-align: center;
    font-size: 40px;
    font-weight: bold;
    color: #035c63;
    line-height: 1.2;
    margin: 8px auto;
}
    </style>
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="logo">SkillSwap</a>

    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="post_skill.php">Post Skill Swap</a>
        <a href="logout.php">Log out</a>
    </div>
</nav>

<div class="form-box">

    <h2 class="skills-heading">Share Your Skills</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">
        <label class="swap">Skill I can teach:</label>
        <input type="text" name="teach" placeholder="e.g. Java, Python, SQL..... " required>
           
        <div class="swap-symbol">⇆</div>

        <label class="swap">Skill I want to learn:</label>
        <input type="text" name="learn" placeholder="e.g. ML, JS, HTML..... " required>

        <textarea name="description" placeholder="Describe your skill swap <30 Words."></textarea>

        <button type="submit" name="post_skill">
            Publish Skill Swap
        </button>

    </form>

</div>

<!--- footer part --->

<footer class="site-footer">
    <div class="footer-content">
        <h3>SkillSwap</h3>
        <p>Learn a skill. Share a skill. Grow together.</p>
        <p>Made with &hearts; by NexByte</p>
        <p class="footer-copy">
            &copy; <?php echo date("Y"); ?> SkillSwap. All rights reserved.
            
        </p>
    </div>
</footer>

</body>
</html>
