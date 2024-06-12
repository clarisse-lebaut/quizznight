<?php
// login.php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nightquiz"; // Mise à jour du nom de la base de données

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Hachage du mot de passe
    $hashed_password = hash('sha256', $password);

    // Vérifier les identifiants dans la base de données
    $sql = "SELECT id, username, roles FROM user WHERE username = :username AND password = :password";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password); // Utilisation du mot de passe haché
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $_SESSION["user_id"] = $result["id"];
        $_SESSION["username"] = $result["username"];
        $_SESSION["roles"] = $result["roles"];
        header("Location: index.php");
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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
        </ul>
    </nav>
</header>

<main>
    <h1>Connexion</h1>
    <?php
    if (!empty($error_message)) {
        echo "<p class='error'>$error_message</p>";
    }
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" name="submit" value="Se connecter" class="btn">
    </form>
</main>

<footer>
    <p>&copy; 2023 Quiz Night. Tous droits réservés.</p>
</footer>
</body>
</html>

<?php
// Fermer la connexion à la base de données
$conn = null;
?>
