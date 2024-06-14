<?php
require '../config/config.php'; // Inclure le fichier de configuration
require '../class/classConnectDB.php'; // Inclure la classe de connexion à la base de données
require '../class/classQuestion.php'; // Inclure la classe pour l'ajout de questions
require '../class/classNavBar.php';
$navBar = new NavConnect();

try {
    // Initialiser la connexion à la base de données
    $dbConnection = new ConnectToDatabase();
    $questionObj = new Question($dbConnection);

    $message = '';
    if ($_POST) {
        $quiz_id = $_POST["quiz_id"];
        $question_text = $_POST["question_text"];
        $message = $questionObj->addQuestion($quiz_id, $question_text);
    }

    // Récupérer les quizzes et les questions
    $quizzes = $questionObj->getQuizzes();
    $questions = $questionObj->getQuestions();

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Questions</title>
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
    <h1>Add Questions to Quiz</h1>
    <form method="post" action="add_questions.php">
        <label for="quiz_id">Select Quiz:</label>
        <select name="quiz_id" id="quiz_id" required>
            <?php foreach ($quizzes as $quiz): ?>
                <option value="<?php echo htmlspecialchars($quiz['id']); ?>"><?php echo htmlspecialchars($quiz['title']); ?>
                </option>
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