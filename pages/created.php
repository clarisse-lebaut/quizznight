<?php
session_start();
require '../class/classUser.php';
require '../class/classConnect.php';
require '../class/classDisconnect.php';

// Vérifie si le formulaire a été soumis
if ($_POST && !isset($_POST['logout'])) {
    // Récupère les données du formulaire
    $user_name = $_POST['username'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];

    // Crée une instance de la classe User
    $user = new User('localhost', 'test', 'root', '');

    // Tente de créer l'utilisateur
    $result = $user->createUser($user_name, $user_email, $user_password);

    if ($result === true) {
        // Stocker les informations de l'utilisateur dans la session
        $_SESSION['username'] = $user_name;

        // Rediriger vers une page de bienvenue après l'inscription
        header("Location: welcome.php");
        exit();
    } else {
        $message = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizNight</title>
</head>
<body>
    <h2>SE CREER UN COMPTE</h2>
    <?php
    // Faire apparaître le nom de l'utilisateur connecter
    if (isset($message)) {
        echo "<p>$message</p>";
    }
    ?>
    <form action="" method="post">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <input type="submit" value="Confirmer">
    </form>
    <br>
    <a href="connexion.php">Déjà un compte ? Se connecter</a>
    <br>
    <p><a href="index.php">Retour à la page d'accueil</a></p>
</body>
</html>
