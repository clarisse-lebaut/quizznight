<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["roles"] != "admin") {
    header("Location: index.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nightquiz";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $quiz_id = $_POST["quiz_id"];
        $question_text = $_POST["question_text"];

        $sql = "INSERT INTO question (quiz_id, question_text) VALUES (:quiz_id, :question_text)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
        $stmt->bindParam(':question_text', $question_text, PDO::PARAM_STR);
        $stmt->execute();

        echo "Question added successfully!";
    }

    // Fetch quizzes
    $sql = "SELECT id, title FROM quiz";
    $stmt = $conn->query($sql);
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch questions
    $sql = "SELECT id, question_text FROM question";
    $stmt = $conn->query($sql);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Questions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<header>        <li><a href="index.php">retour à l'index</a></li>
</header>
<body>
    <h1>Add Questions to Quiz</h1>
    <form method="post" action="add_questions.php">
        <label for="quiz_id">Select Quiz:</label>
        <select name="quiz_id" id="quiz_id" required>
            <?php foreach ($quizzes as $quiz): ?>
                <option value="<?php echo htmlspecialchars($quiz['id']); ?>"><?php echo htmlspecialchars($quiz['title']); ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <label for="question_text">Question:</label>
        <input type="text" id="question_text" name="question_text" required><br><br>
        <input type="submit" value="Add Question">
    </form>

    <h2>Existing Questions</h2>
    <ul>
        <?php foreach ($questions as $question): ?>
            <li><?php echo htmlspecialchars($question['question_text']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
