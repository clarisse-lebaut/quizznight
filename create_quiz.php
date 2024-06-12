<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nightquiz";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $creator_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if ($creator_id !== null) {
        // Use prepared statements to prevent SQL injection
        $sql = "INSERT INTO quiz (title, description, creator_id) VALUES (:title, :description, :creator_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':creator_id', $creator_id);
        $stmt->execute();

        header('Location: index.php');
        exit();
    } else {
        // Handle the case where the creator_id is not available
        // For example, you can redirect the user to the login page or display an error message
        echo "Error: Creator ID is not set.";
    }
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

