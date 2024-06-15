<?php
// Inclure le fichier de configuration
require '../config/config.php';
// Method to connect appli to DataBase
require '../class/classConnectDB.php';
$dbConnection = new ConnectToDatabase();
$connexion = $dbConnection->getConnexion();

$error_message = "";

if ($_POST) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Hachage du mot de passe
    $hashed_password = hash('sha256', $password);

    // Vérifier les identifiants dans la base de données
    $sql = "SELECT id, username, roles FROM user WHERE username = :username AND password = :password";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password); // Utilisation du mot de passe haché
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $_SESSION["user_id"] = $result["id"];
        $_SESSION["username"] = $result["username"];
        $_SESSION["roles"] = $result["roles"];
        header("Location: ./welcome.php");
        exit();
    } else {
        $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Connexion - Quiz Night</title>
    <link rel="stylesheet" href="../styles/connect.css">
</head>

<body>

    <main>
        <h1>CONNEXION</h1>
        <?php
        if (!empty($error_message)) {
            echo "<p class='error'>$error_message</p>";
        }
        ?>
        <form class="box" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label class="label" for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required>

            <label class="label" for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="submit">Se connecter</button>
            <br>
            <a class="a_style" href="../index.php">Accueil</a>
            <br>
            <a class="a_style" href="../pages/create_user.php">Nouveau ? Crée toi un compte !</a>
            <p>&copy; 2023 Quiz Night. Tous droits réservés.</p>
        </form>
    </main>
</body>

</html>

<?php
// Fermer la connexion à la base de données
$connexion = null;
?>