<?php
// Include files and instantiate it
require '../config/config.php';
require '../class/classConnectDB.php';
$dbConnection = new ConnectToDatabase();
$connexion = $dbConnection->getConnexion();

// Instantiate var to have an error message
$error_message = "";

if ($_POST) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Hacsh password
    $hashed_password = hash('sha256', $password);

    // Check IDs in database
    $sql = "SELECT id, username, roles FROM user WHERE username = :username AND password = :password";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':username', $username);
    // Use hacsh password
    $stmt->bindParam(':password', $hashed_password);
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
        <!-- Condition for error message -->
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
// Close database connection
$connexion = null;
?>