<?php
// initialiser la session
session_start();

// inclure la classe de connexion à la base de donnée et l'instancier
require '../class/classConnect.php';
$dbConnection = new ConnectToDatabase();
$connexion = $dbConnection->getConnexion();

// inclure la classe de la barre de navigation après connexion et l'instancier
require '../class/classNavConnect.php';
$navBar = new NavConnect();

// Initialiser le tableau des questions et réponses si ce n'est pas déjà fait
if (!isset($_SESSION['questions_answers'])) {
    $_SESSION['questions_answers'] = [];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Bienvenue</title>
</head>

<header>
    <style>
        h2 {
            text-align: center;
        }
    </style>
    <?php
    // Afficher la barre de navigation
    $navBar->NavConnect();
    ?>
    <h2><?php echo htmlspecialchars($_SESSION['username']); ?>, entrez vos questions et vos réponses !</h2>
</header>

<body>
    <style>
        form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }
    </style>
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
    <hr width="250px">
    <div>

        <?php
        // Gestion de l'ajout des questions et des réponses
        if (isset($_POST['add_question'])) {
            // Récupérer les données du formulaire
            $question = $_POST['question'];
            $answer = $_POST['answer'];

            // Échapper les caractères spéciaux pour éviter les injections XSS
            $escaped_question = htmlspecialchars($question);
            $escaped_answer = htmlspecialchars($answer);

            // Ajouter la question et la réponse au tableau des questions et réponses
            $_SESSION['questions_answers'][] = [
                'question' => $escaped_question,
                'answer' => $escaped_answer
            ];

            // Afficher toutes les questions et réponses
            if (isset($_SESSION['questions_answers']) && count($_SESSION['questions_answers']) > 0) {
                foreach ($_SESSION['questions_answers'] as $qa) {
                    echo "<div class='box'>";
                    echo "<article id='etiquette'>";
                    echo "<p>Q : " . $qa['question'] . "</p>";
                    echo "<p>R : " . $qa['answer'] . "</p>";
                    echo "</article>";
                    echo "</div>";
                }
            }
        }
        ?>
    </div>
    <?php
    //Gestion de la suppression des questions et des réponses 
    ?>
</body>
<style>
    .box {
        display: flex;
    }

    #etiquette {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
</style>

</html>