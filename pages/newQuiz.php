<?php
// Inclure la classe de connexion et initialiser la session
session_start();
require '../class/classConnect.php';
require '../class/classNavConnect.php';

// Créer une instance de la classe de connexion
$dbConnection = new ConnectToDatabase();
$connexion = $dbConnection->getConnexion();

// Créer une instance de la classe NavConnect
$navBar = new NavConnect();

// Gestion de l'ajout de questions
if (isset($_POST['add_question'])) {
    // Récupérer les données du formulaire
    $question_text = $_POST['question'];
    $answer = $_POST['answer'];

    // Initialiser $_SESSION['questions'] si ce n'est pas déjà fait
    if (!isset($_SESSION['questions'])) {
        $_SESSION['questions'] = array();
    }

    // Ajouter la question à $_SESSION['questions']
    $_SESSION['questions'][] = array(
        'question' => $question_text,
        'answer' => $answer
    );
}

// Gestion de la suppression de question
if (isset($_GET['delete'])) {
    $index = $_GET['index'];
    if (isset($_SESSION['questions'][$index])) {
        unset($_SESSION['questions'][$index]);
        // Réindexer $_SESSION['questions'] après la suppression
        $_SESSION['questions'] = array_values($_SESSION['questions']);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Bienvenue</title>
</head>

<body>
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