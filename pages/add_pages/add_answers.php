<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["roles"] != "admin") {
    header("Location: ../welcome.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiznight";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $question_id = $_POST["question_id"];
        $answer_text = $_POST["answer_text"];

        $sql = "INSERT INTO answer (question_id, answer_text) VALUES (:question_id, :answer_text)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->bindParam(':answer_text', $answer_text, PDO::PARAM_STR);
        $stmt->execute();

        echo "Answer added successfully!";
    }

    // Fetch questions
    $sql = "SELECT id, question_text FROM question";
    $stmt = $conn->query($sql);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch answers
    $sql = "SELECT id, answer_text FROM answer";
    $stmt = $conn->query($sql);
    $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Answers</title>
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
    <h1>Add Answers to Question</h1>
    <form method="post" action="add_answers.php">
        <label for="question_id">Select Question:</label>
        <select name="question_id" id="question_id" required>
            <?php foreach ($questions as $question): ?>
                <option value="<?php echo htmlspecialchars($question['id']); ?>">
                    <?php echo htmlspecialchars($question['question_text']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        <label for="answer_text">Answer:</label>
        <input type="text" id="answer_text" name="answer_text" required><br><br>
        <input type="submit" value="Add Answer">
    </form>

    <h2>Existing Answers</h2>
    <ul>
        <?php foreach ($answers as $answer): ?>
            <li><?php echo htmlspecialchars($answer['answer_text']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>

</html>