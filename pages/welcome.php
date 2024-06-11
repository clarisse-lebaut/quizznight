<?php
    session_start();
    require '../class/classConnect.php';
    require '../class/classDisconnect.php';

    if (!isset($_SESSION['username'])) {
        header("Location: connexion.php");
        exit();
    }

    // Redirection vers la création d'un nouveau quizz
    if (isset($_POST['newQuizz'])){
        header("Location: newQuiz.php");
        exit();
    }

    // Créer une instance de la classe Disconnect
    $disconnect = new Disconnect();
    // Appeler la méthode pour gérer la déconnexion
    $disconnect->disconnectModul();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Bienvenue</title>
</head>
<body>
    <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
    <form action="" method="POST">
        <input type="submit" name="newQuizz" value="Créer un quizz">
    </form>
    <form action="" method="post">
        <input type="submit" name="logout" value="Se déconnecter">
    </form>
</body>
</html>
