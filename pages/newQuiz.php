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

    // Créer une instance de la classe de connexion
    $dbConnection = new ConnectToDatabase();
    $connexion = $dbConnection->getConnexion();
    $navBar = new NavConnect();

    // Gestion de l'ajout de question
    if (isset($_GET['add_question'])) {
        $question = $_GET['question'];
        $answer = $_GET['answer'];
        $_SESSION['questions'][] = array('question' => $question, 'answer' => $answer);
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

    <form action="" method="GET"> 
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
                    <p>Q : {$question['question']}</p>
                    <p>A : {$question['answer']}</p>
                    <button type='submit' name='delete'>Supprimer</button>
                </form>";
        }
    }
    ?>

</body>
</html>
