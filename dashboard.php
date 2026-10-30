
<?php
session_start();
include "db.php";

// Only logged-in students can open this page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get the logged-in student's ID
$userId = $_SESSION['user_id'];

// Get the student's profile
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Get all skill posts

 // Get all skill posts
$sql = "SELECT skills.*, users.name, users.email, users.phone
        FROM skills
        JOIN users ON skills.user_id = users.id
        ORDER BY skills.id DESC";

$skills = mysqli_query($conn, $sql);


// Count listings created by the logged-in student
$sql = "SELECT COUNT(*) AS my_count
        FROM skills
        WHERE user_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

$myListings = $row['my_count'];

mysqli_stmt_close($stmt);


// Count all listings from all students
$sql = "SELECT COUNT(*) AS total_count FROM skills";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$totalListings = $row['total_count'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - SkillSwap</title>
    <link rel="stylesheet" href="style.css">
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

<div class="container">

    <h1>
        Welcome,
        <?php echo htmlspecialchars($user['name']); ?>!
    </h1>
    <br>


	<!--- Profile Part --->

    
 

<div class="stats">

    <div class="stat-card">
        <h3>Email</h3>
        <p class="profile-value">
            <?php echo htmlspecialchars($user['email']); ?>
        </p>
    </div>

    <div class="stat-card">
        <h3>Contact</h3>
        <p class="profile-value">
            <?php echo htmlspecialchars($user['phone']); ?>
        </p>
    </div>

    <div class="stat-card">
        <h3>Course</h3>
        <p class="profile-value">
            <?php echo htmlspecialchars($user['course']); ?>
        </p>
    </div>

</div>


	<!--- Stats Part --->

	<div class="stats">

    		<div class="stat-card">
        	<h3>My Listings</h3>
        	<p><?php echo $myListings; ?></p>
    	</div>

    	<div class="stat-card">
        	<h3>Total Listings</h3>
        	<p><?php echo $totalListings; ?></p>
    	</div>

	</div>

        
    </div>

    <a href="post_skill.php" class="button-post-skill">
        + Post a Skill Swap
    </a>
    <br><br>

    <h2 class="skills-heading">Available Skill Swaps</h2>
    
    <div class="skills-grid">

    <?php if (mysqli_num_rows($skills) > 0) { ?>

        <?php while ($skill = mysqli_fetch_assoc($skills)) { ?>

            
        <div class="skill-card">

   	 <h3>   
             
       	 	<?php echo htmlspecialchars($skill['skill_to_teach']); ?>
            
    	 </h3>
         

    		<p>
        	<b>Student:</b>
        	<?php echo htmlspecialchars($skill['name']); ?>
   	 </p>

    	<p>
        	<b>Wants to learn:</b>
        	<?php echo htmlspecialchars($skill['skill_to_learn']); ?>
   	 </p>

    	<p>
        	
<p>
    <strong>Description:</strong><br>
    <?php
        $description = $skill['description'] ?? '';

        if (strlen($description) > 100) {
            $description = substr($description, 0, 40) . '...';
        }

        echo nl2br(htmlspecialchars($description));
    ?>
</p>

    	</p>

    	<a href="mailto:<?php echo htmlspecialchars($skill['email'], ENT_QUOTES); ?>">
        	Contact by Email
    	</a>

    	<?php if (!empty($skill['phone'])) { ?>
        	<a href="tel:<?php echo htmlspecialchars($skill['phone'], ENT_QUOTES); ?>">
            	Contact by Phone
        	</a>
    	<?php } ?>

	</div>


        <?php } ?>

</div>

    <?php } else { ?>

        <p>No skills posted yet. Be the first!</p>

    <?php } ?>

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
