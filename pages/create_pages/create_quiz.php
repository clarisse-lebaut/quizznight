<?php
require '../../config/config.php'; // Inclure le fichier de configuration
require '../../class/classConnectDB.php'; // Inclure la classe de connexion à la base de données
require '../../class/classQuizz.php'; // Inclure la classe Quiz

$dbConnection = new ConnectToDatabase();
$quiz = new Quiz($dbConnection);

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_POST) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $creator_id = $_SESSION['user_id'];

    $quiz->addQuiz($title, $description, $creator_id);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Créer un quiz</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="admin.php">Administration</a></li>
                <li><a href="create_user.php">Créer un utilisateur</a></li>
                <li><a href="login.php">Connexion</a></li>
                <li><a href="add_answers.php">Ajouter des réponses</a></li>
                <li><a href="add_questions.php">Ajouter des questions</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Créer un quiz</h1>
        <form method="post" action="">
            <label for="title">Titre:</label>
            <input type="text" id="title" name="title" required>
            <br>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
            <br>
            <input type="submit" value="Créer">
        </form>
    </main>

    <footer>
        <p>&copy; 2023 Quiz Night. Tous droits réservés.</p>
    </footer>
</body>

</html>
