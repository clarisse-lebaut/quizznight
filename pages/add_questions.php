<?php
require '../config/config.php'; // Inclure le fichier de configuration de session
require '../class/classConnectDB.php'; // Inclure la classe de connexion à la base de données
require '../class/classQuestion.php'; // Inclure la classe pour la gestion des questions
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
    $questionObj = new Question($dbConnection);

    $message = '';
    if ($_POST) {
        // Code pour ajouter une nouvelle question
        $quiz_id = $_POST["quiz_id"];
        $question_text = $_POST["question_text"];
        $questionObj->addQuestion($quiz_id, $question_text);
    }

    // Récupérer les quiz disponibles
    $quizs = $questionObj->getQuizzes(); // Méthode à implémenter dans votre classe Question

    // Récupérer les questions
    $questions = $questionObj->getQuestions();

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Ajouter une question</title>
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
    <h1>Ajouter une question</h1>
    <main>
        <div class="container_box">
            <form method="POST" action="">
                <label for="quiz_id">Quizz : </label>
                <select id="quiz_id" name="quiz_id" required>
                    <?php foreach ($quizs as $quiz): ?>
                        <option value="<?php echo htmlspecialchars($quiz['id']); ?>">
                            <?php echo htmlspecialchars($quiz['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label for="question_text">Question : </label>
                <input type="text" id="question_text" name="question_text" required>
                <input id="button" type="submit" value="Ajouter">
            </form>
        </div>

        <h2>Questions</h2>
        <div class="form_two">
            <?php
            $i = 1;
            foreach ($questions as $question):
                ?>
                <?php
                echo "<div>";
                echo $i . " . " . htmlspecialchars($question['question_text']);
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