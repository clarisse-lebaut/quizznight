<?php
require '../config/config.php'; // Inclure le fhcier de configuration de session
require '../class/classConnectDB.php'; // Inclure la classe de connexion à la base de données
require '../class/classAnswer.php'; // Inclure la classe pour l'ajout de réponses
require '../class/classNavBar.php';
$navBar = new NavConnect();
require '../class/classFooter.php';
$footer = new Footer();

if (!isset($_SESSION["user_id"]) || $_SESSION["roles"] != "admin") {
    header("Location: ./welcome.php");
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
    <link rel="stylesheet" href="../styles/body.css">
    <link rel="stylesheet" href="../styles/navbar.css">
    <link rel="stylesheet" href="../styles/add.css">
</head>
<header>
    <nav class="navbar">
        <?php
        $navBar->NavConnect();
        ?>
    </nav>
</header>

<body>
    <h1>Ajouter une réponse</h1>
    <main>
        <div class="container_box">
            <form method="post" action="">
                <label for="question_id">Question:</label>
                <select id="question_id" name="question_id" required>
                    <?php foreach ($questions as $question): ?>
                        <option value="<?php echo htmlspecialchars($question['id']); ?>">
                            <?php echo htmlspecialchars($question['question_text']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label for="answer_text">Réponse:</label>
                <input type="text" id="answer_text" name="answer_text" required>
                <input id="button" type="submit" value="Ajouter">
            </form>
        </div>

        <h2>Réponses</h2>
        <div class="form_two">
            <?php
            $i = 1;
            foreach ($answers as $answer):
                ?>
                <?php
                echo "<div>";
                echo $i . " . " . htmlspecialchars($answer['answer_text']);
                echo "</div>";
                ?>
                <?php
                $i++;
                ?>
                <?php
            endforeach;
            ?>
        </div>
    </main>
    <footer>
        <?php
        $footer->footer();
        ?>
    </footer>
</body>

</html>