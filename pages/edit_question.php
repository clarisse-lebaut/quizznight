<?php
require '../class/classNavBar.php';
$navBar = new NavConnect();
// Inclure le fichier de configuration
require '../config/config.php';

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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["delete"])) {
            // Handle delete question
            $question_id = $_POST["question_id"];
            $sql = "DELETE FROM question WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $question_id, PDO::PARAM_INT);
            $stmt->execute();

            // Optionally, remove the question from the session if stored there
            if (isset($_SESSION['questions'][$question_id])) {
                unset($_SESSION['questions'][$question_id]);
            }

            echo "Question deleted successfully!";
            header("Location: ./admin.php"); // Redirect back to admin page after deletion
            exit();
        } else {
            // Handle update question
            $question_id = $_POST["question_id"];
            $question_text = $_POST["question_text"];

            $sql = "UPDATE question SET question_text = :question_text WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':question_text', $question_text, PDO::PARAM_STR);
            $stmt->bindParam(':id', $question_id, PDO::PARAM_INT);
            $stmt->execute();

            echo "Question updated successfully!";

            // Re-fetch the updated question to display in the form
            $sql = "SELECT id, question_text FROM question WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $question_id, PDO::PARAM_INT);
            $stmt->execute();
            $question = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } else {
        $question_id = $_GET["id"];
        $sql = "SELECT id, question_text FROM question WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $question_id, PDO::PARAM_INT);
        $stmt->execute();
        $question = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Question</title>
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
    <h1>Edit Question</h1>

    <a href="./admin.php">Retour sur la page adminstrateur</a>

    <form method="post" action="edit_question.php">
        <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question['id']); ?>">
        <label for="question_text">Question:</label>
        <input type="text" id="question_text" name="question_text"
            value="<?php echo htmlspecialchars($question['question_text']); ?>" required><br><br>
        <input type="submit" value="Update Question">
    </form>

    <form method="post" action="" style="margin-top: 20px;">
        <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question['id']); ?>">
        <input type="submit" name="delete" value="Delete Question"
            onclick="return confirm('Are you sure you want to delete this question?');">
    </form>

</body>

</html>