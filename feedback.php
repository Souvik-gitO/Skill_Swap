
<?php
session_start();
include "db.php";

$message = "";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $rating = $_POST['rating'];
    $comment = trim($_POST['comment']);

    $reviewer_id = $_SESSION['user_id'];

    // Find student by email
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $message = "Student email not found.";
    } else {

        $student = $result->fetch_assoc();
        $receiver_id = $student['id'];

        if ($receiver_id == $reviewer_id) {
            $message = "You cannot give feedback to yourself.";
        } elseif ($rating < 1 || $rating > 5) {
            $message = "Please select a valid rating.";
        } elseif ($comment == "") {
            $message = "Please write a comment.";
        } else {

            $sql = "INSERT INTO feedback
                    (reviewer_id, receiver_id, rating, comment)
                    VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "iiis",
                $reviewer_id,
                $receiver_id,
                $rating,
                $comment
            );

            if ($stmt->execute()) {
                $message = "Feedback submitted successfully!";
            } else {
                $message = "Something went wrong.";
            }
        }
    }

    $stmt->close();
}

$student_id = (int)($_GET['student_id'] ?? 0);

$sql = "SELECT feedback.*,
        users.name AS reviewer_name,
        teacher.name AS teacher_name
        FROM feedback
        JOIN users ON feedback.reviewer_id = users.id
        JOIN users AS teacher ON feedback.receiver_id = teacher.id
        ORDER BY feedback.id DESC";

$reviews = mysqli_query($conn, $sql);
?>



<!DOCTYPE html>
<html>
<head>
    <title>Student Feedback</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="fb-container">

    <div class="fb-card">
        <a href="dashboard.php" class="fb-back">
            ← Back to Dashboard
        </a>

        <h2>Student Feedback</h2>
        <p class="fb-subtitle">Share your learning experience with us</p>

        <?php if ($message != "") { ?>
            <p class="fb-message">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php } ?>

        <form method="POST">

            <div class="fb-group">
                <label>Who taught you?</label>
                <input type="email" name="email"
                       placeholder="Enter student's email" required>
            </div>

            <div class="fb-group">
                <label>Rate your experience</label>

                <div class="fb-rating">
                    <?php for ($i = 5; $i >= 1; $i--) { ?>
                        <label class="fb-star-option">
                            <input type="radio" name="rating"
                            value="<?php echo $i; ?>" required>
                            <span>★</span>
                        </label>
                    <?php } ?>
                </div>

                <small>Select 1 to 5 stars</small>
            </div>

            <div class="fb-group">
                <label>Your Feedback</label>
                <textarea name="comment"
                          placeholder="Write your experience..."
                          rows="5" required></textarea>
            </div>

            <button type="submit" class="fb-submit">
                Submit Feedback
            </button>

        </form>
    </div>

    <hr class="feedback-divider">
    <div class="fb-card" id="student-reviews">
         <h2 id="student-reviews">Student Reviews</h2>

        <div class="reviews-grid">

        <?php
        if (mysqli_num_rows($reviews) > 0) {

            while ($review = mysqli_fetch_assoc($reviews)) {
                echo '<div class="review-box">';

                echo "<h3>Student: " . htmlspecialchars($review['teacher_name']) . "</h3>";

                echo "<p>Reviewed by: " . htmlspecialchars($review['reviewer_name']) . "</p>";

                echo "<p>Rating: " . str_repeat("⭐", $review['rating']) . "</p>";

                echo "<p>" . nl2br(htmlspecialchars($review['comment'])) . "</p>";

                echo "</div>";
            }

        } else {
            echo "<p>No reviews yet.</p>";
        }
        ?>
    </div>


    </div>

</div>

</body>
</html>

