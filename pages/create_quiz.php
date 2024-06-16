<?php
// Include files and instantiate it
require '../config/config.php';
require '../class/classConnectDB.php';
$dbConnection = new ConnectToDatabase();
require '../class/classQuizz.php';
$quiz = new Quiz($dbConnection);
require '../class/classNavBar.php';
$navBar = new NavConnect();
require '../class/classFooter.php';
$footer = new Footer();

// Checking access permissions
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Method _POST to put data in database
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
    <link rel="stylesheet" href="../styles/body.css">
    <link rel="stylesheet" href="../styles/navbar.css">
    <link rel="stylesheet" href="../styles/create_quizz.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <?php
            $navBar->NavConnect();
            ?>
        </nav>
    </header>

    <main>
        <h1>Créer un quiz</h1>
        <form method="post" action="">
            <label for="title">Titre:</label>
            <input type="text" id="title" name="title" required>
            <br>
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="5" cols="33" required></textarea>
            <button type="submit">Créer</button>
        </form>
    </main>

    <footer>
        <?php
        $footer->footer();
        ?>
    </footer>
</body>

</html>