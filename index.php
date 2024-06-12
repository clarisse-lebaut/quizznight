<?php
// Start the session
session_start();

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nightquiz";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die(); // Stop script execution on connection failure
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Night</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <?php if (isLoggedIn() && $_SESSION["roles"] == "admin"): ?>
                <li><a href="admin.php">Administration</a></li>
                <li><a href="create_quiz.php">Créer un quiz</a></li>
            <?php endif; ?>
            <li><a href="create_user.php">Créer un utilisateur</a></li>
            <li><a href="login.php">Connexion</a></li>
            <li><a href="add_answers.php">Ajouter des réponses</a></li>
            <li><a href="add_questions.php">Ajouter des questions</a></li>
            <li><a href="disconnect.php">Déconnexion</a></li>
        </ul>
    </nav>
</header>
<main>
    <h1>Quiz Night</h1>
    <p>Bienvenue sur Quiz Night ! Voici la liste des quiz disponibles :</p>
    <?php
// Fetch and display quizzes
$sql = "SELECT q.id, q.title, q.description, u.username AS creator_id FROM quiz q JOIN user u ON q.creator_id = u.id";
$stmt = $conn->query($sql);

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<div class='quiz-item'>";
        echo "<h2>" . htmlspecialchars($row["title"]) . "</h2>";
        echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
        echo "<p>Créé par: " . (isset($row["creator_id"]) ? htmlspecialchars($row["creator_id"]) : 'N/A') . "</p>";
        if (isLoggedIn()) {
            echo "<a href='quiz.php?id=" . htmlspecialchars($row["id"]) . "' class='btn'>Commencer le quiz</a>";
        }
        if (isLoggedIn() && $_SESSION["roles"] == "admin") {
            echo "<a href='delete_quiz.php?id=" . htmlspecialchars($row["id"]) . "' class='btn'>Supprimer le quiz</a>";
        }
        echo "</div>";
    }
} else {
    echo "<p>Aucun quiz trouvé.</p>";
}

    
    // Close the database connection
    $conn = null;
    ?>
</main>
<footer>
    <p>&copy; 2023 Quiz Night. Tous droits réservés.</p>
</footer>
</body>
</html>
