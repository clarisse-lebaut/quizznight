<?php
// Start the session
session_start();

// Method to connect appli to DataBase
require '../class/classConnectDB.php';
$dbConnection = new ConnectToDatabase();
$connexion = $dbConnection->getConnexion();

// Function to check if the user is logged in
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Quiz Night</title>
    <link rel="stylesheet" href="../../index.css">
    <link rel="stylesheet" href="../styles/nav.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <ul>
                <li><a class="a_style" href="./welcome.php">Accueil</a></li>
                <?php if (isLoggedIn() && $_SESSION["roles"] == "admin"): ?>
                    <li><a class="a_style" href="./create_pages/create_quiz.php">Créer un quiz</a></li>
                    <li><a class="a_style" href="../add_pages/add_answers.php">Ajouter des réponses</a></li>
                    <li><a class="a_style" href="../add_pages/add_questions.php">Ajouter des questions</a></li>
                    <li><a class="a_style" href="./admin.php">Gestion</a></li>
                    <li><a class="a_style" href="./disconnect.php">Déconnexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Quiz Night</h1>
        <p class="title">Bienvenue sur Quiz Night !</p>
        <p class="title">Choisissez un quizz :</p>
        <hr width="250px">
        <div class="grid_container">
            <?php
            // Fetch and display quizzes
            $sql = "SELECT q.id, q.title, q.description, u.username AS creator_id FROM quiz q JOIN user u ON q.creator_id = u.id";
            $stmt = $connexion->query($sql);

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
            $connexion = null;
            ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2023 Quiz Night. Tous droits réservés.</p>
    </footer>
</body>

</html>