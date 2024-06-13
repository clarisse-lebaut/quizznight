<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["roles"] !== "admin") {
    header("Location: ../../welcome.php");
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
    <link rel="stylesheet" href="styles.css">
</head>

<header>
    <nav>
        <ul>
            <li><a href="../welcome.php">Retour à l'index</a></li>
            <li><a href="../admin.php">Administration</a></li>
        </ul>
    </nav>
</header>


<body>
    <h1>Edit Question</h1>
    <form method="post" action="edit_question.php">
        <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question['id']); ?>">
        <label for="question_text">Question:</label>
        <input type="text" id="question_text" name="question_text"
            value="<?php echo htmlspecialchars($question['question_text']); ?>" required><br><br>
        <input type="submit" value="Update Question">
    </form>
    <form method="post" action="delete_question.php" style="margin-top: 20px;">
        <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question['id']); ?>">
        <input type="submit" name="delete" value="Delete Question"
            onclick="return confirm('Are you sure you want to delete this question?');">
    </form>
</body>

</html>