<?php
session_start();
require '../class/classConnect.php';
require '../class/classDisconnect.php';

// Initialise la connexion à la base de données
$db = new ConnectToDatabase();
$connexion = $db->getConnexion();

// Vérifie si le formulaire a été soumis
if ($_POST) {
    // Récupère les données du formulaire
    $user_name = $_POST['username'];
    $user_password = $_POST['password'];

    // Requête SQL pour vérifier les informations de connexion de l'utilisateur
    $sql = "SELECT * FROM user WHERE username = :username";

    try {
        // Préparer la requête
        $stmt = $connexion->prepare($sql);
        // Lier les paramètres
        $stmt->bindParam(':username', $user_name);
        // Exécuter la requête
        $stmt->execute();

        // Récupérer les résultats
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($user_password, $user['password'])) {
            // Les informations de connexion sont correctes
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            // Rediriger vers une page de bienvenue après la connexion
            header("Location: welcome.php");
            exit();
        } else {
            $message = "Nom d'utilisateur ou mot de passe incorrect";
        }
    } catch (PDOException $e) {
        $message = "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion
    $connexion = null;
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
    <h2>CONNEXION</h2>
    <?php
    // Faire apparaître le message
    if (isset($message)) {
        echo "<p>$message</p>";
    }
    ?>
    <form action="" method="post">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Se connecter">
    </form>
    <br>
    <a href="created.php">Nouveau ? Créez vous un compte</a>
    <br>
    <p><a href="index.php">Retour à la page d'accueil</a></p>
</body>
</html>
