<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Bienvenue</title>
</head>
<body>
    <?php
    // Inclure la classe de connexion et initialiser la session
    session_start();
    require '../class/classConnect.php';
    require '../class/classNavConnect.php';
    require '../class/classQuizz.php';
    require '../class/classQuestion.php';

    // Créer une instance de la classe de connexion
    $dbConnection = new ConnectToDatabase();
    $connexion = $dbConnection->getConnexion();
    $navBar = new NavConnect();
    $quiz_request = new createQuizz();
    $question_request = new Question();

    // Gestion de l'ajout de quiz et de questions
    if (isset($_POST['add_question'])) {
        $question_text = $_POST['question'];
        $answer = $_POST['answer'];

        if (!isset($_SESSION['quiz_id'])) {
            $quiz_title = "Quiz de " . htmlspecialchars($_SESSION['username']); // Exemple de titre
            $creatorId = $_SESSION['user_id'];
            $_SESSION['quiz_id'] = $quiz_request->createNewQuizz($connexion, $quiz_title, $creatorId);
        }

        $quiz_id = $_SESSION['quiz_id'];
        $_SESSION['questions'][] = array('question' => $question_text, 'answer' => $answer);
        $question_request->createQuestions($connexion, $question_text, $quiz_id);
    }

    // Gestion de la suppression de question
    if (isset($_GET['delete'])) {
        $index = $_GET['index'];
        unset($_SESSION['questions'][$index]);
        $_SESSION['questions'] = array_values($_SESSION['questions']);
    }
    ?>

    <?php
    // Afficher la barre de navigation
    $navBar->NavConnect();
    ?>

    <h2><?php echo htmlspecialchars($_SESSION['username']); ?>, entrez vos questions et vos réponses !</h2>

    <form action="" method="POST"> 
        <div class="questions">
            <label for="question">Taper une question</label><br>
            <input id="question" type="text" name="question" required>
        </div>
        <div class="answer">
            <label for="answer">Taper la réponse</label><br>
            <input id="answer" type="text" name="answer" required>
        </div>
        <input type="submit" name="add_question" value="Confirmer">
    </form>

    <p>Vos questions :</p>

    <?php
    // Vérifier s'il y a des questions enregistrées dans la session
    if (isset($_SESSION['questions'])) {
        // Afficher toutes les questions enregistrées avec un bouton de suppression
        foreach ($_SESSION['questions'] as $index => $question) {
            echo "
                <form method='GET' style='display: inline;'>
                    <input type='hidden' name='index' value='$index'>
                    <p>Q : " . htmlspecialchars($question['question']) . "</p>
                    <p>A : " . htmlspecialchars($question['answer']) . "</p>
                    <button type='submit' name='delete'>Supprimer</button>
                </form>";
        }
    }
    ?>
</body>
</html>
