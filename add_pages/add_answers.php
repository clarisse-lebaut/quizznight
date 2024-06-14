<?php
require '../config/config.php'; // Inclure le fhcier de configuration de session
require '../class/classConnectDB.php'; // Inclure la classe de connexion à la base de données
require '../class/classAnswer.php'; // Inclure la classe pour l'ajout de réponses

if (!isset($_SESSION["user_id"]) || $_SESSION["roles"] != "admin") {
    header("Location: ../welcome.php");
    exit();
}

try {
    // Initialiser la connexion à la base de données
    $dbConnection = new ConnectToDatabase();
    $answerObj = new Answer($dbConnection);

    $message = '';
    if ($_POST) {
        $question_id = $_POST["question_id"];
        $answer_text = $_POST["answer_text"];
        $message = $answerObj->addAnswer($question_id, $answer_text);
    }

    // Récupérer les questions et les réponses
    $questions = $answerObj->getQuestions();
    $answers = $answerObj->getAnswers();

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter une réponse</title>
</head>
<body>
    <h1>Ajouter une réponse</h1>
    <form method="post" action="">
        <label for="question_id">Question:</label>
        <select id="question_id" name="question_id" required>
            <?php foreach ($questions as $question): ?>
                    <option value="<?php echo htmlspecialchars($question['id']); ?>"><?php echo htmlspecialchars($question['question_text']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="answer_text">Réponse:</label>
        <input type="text" id="answer_text" name="answer_text" required>
        <br>
        <input type="submit" value="Ajouter">
    </form>

    <?php if ($message): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <h2>Réponses existantes</h2>
    <ul>
        <?php foreach ($answers as $answer): ?>
                <li><?php echo htmlspecialchars($answer['answer_text']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
