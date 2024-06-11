<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: connexion.php");
    exit();
}

// Vérifie si l'utilisateur a demandé à se déconnecter
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: connexion.php"); // Redirige vers la page d'accueil après déconnexion
    exit();
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
    <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
    <p>Crée le quizz</p>
    <p><a href="welcome.php">Retour à la page d'accueil de l'utlisateur</a></p>
</body>
</html>
