<?php
require '../class/classNavBar.php';
$navBar = new NavConnect();

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["roles"] !== "admin") {
    header("Location: ./welcome.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiznight";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["delete"])) {
            // Handle delete quiz
            $quiz_id = $_POST["quiz_id"];
            $sql = "DELETE FROM quiz WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $quiz_id, PDO::PARAM_INT);
            $stmt->execute();
            echo "Quiz deleted successfully!";
            header("Location: ./admin.php"); // Redirect back to admin page after deletion
            exit();
        } else {
            // Handle update quiz
            $quiz_id = $_POST["quiz_id"];
            $title = $_POST["title"];
            $description = $_POST["description"];

            $sql = "UPDATE quiz SET title = :title, description = :description WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':id', $quiz_id, PDO::PARAM_INT);
            $stmt->execute();
            echo "Quiz updated successfully!";

            // Re-fetch the updated quiz to display in the form
            $sql = "SELECT id, title, description FROM quiz WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $quiz_id, PDO::PARAM_INT);
            $stmt->execute();
            $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } else {
        if (isset($_GET["id"])) {
            $quiz_id = $_GET["id"];
            $sql = "SELECT id, title, description FROM quiz WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $quiz_id, PDO::PARAM_INT);
            $stmt->execute();
            $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$quiz) {
                echo "Quiz not found.";
                exit();
            }
        } else {
            echo "No quiz ID provided.";
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Quiz</title>
    <link rel="stylesheet" href="../styles/nav.css">
    <link rel="stylesheet" href="../styles/body.css">
</head>

<header>
    <nav class="navbar">
        <?php
        $navBar->NavConnect();
        ?>
    </nav>
</header>

<body>
    <h1>Edit Quiz</h1>
    <a href="./admin.php">Retour sur la page adminstrateur</a>
    <?php if (isset($quiz)): ?>
        <form method="post" action="edit_quiz.php">
            <input type="hidden" name="quiz_id" value="<?php echo htmlspecialchars($quiz['id']); ?>">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($quiz['title']); ?>"
                required><br><br>
            <label for="description">Description:</label>
            <textarea id="description"
                name="description"><?php echo htmlspecialchars($quiz['description']); ?></textarea><br><br>
            <input type="submit" value="Update Quiz">
        </form>
        <form method="post" action="edit_quiz.php" style="margin-top: 20px;">
            <input type="hidden" name="quiz_id" value="<?php echo htmlspecialchars($quiz['id']); ?>">
            <input type="submit" name="delete" value="Delete Quiz"
                onclick="return confirm('Are you sure you want to delete this quiz?');">
        </form>
    <?php endif; ?>
</body>

</html>