<?php
require '../config/config.php'; // Inclure le fichier de configuration
require '../class/classConnectDB.php';
require '../class/classNavBar.php';
$navBar = new NavConnect();
$dbConnection = new ConnectToDatabase();
$connexion = $dbConnection->getConnexion();

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Quiz Night</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="../styles/nav.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <?php
            $navBar->NavConnect();
            ?>
        </nav>
    </header>
    <main>
        <h1>Quiz Night</h1>
        <p class="title">Bienvenue sur Quiz Night <?php echo htmlspecialchars($_SESSION['username']); ?> !</p>
        <p class="title">Choisissez un quiz :</p>
        <hr width="250px">
        <div class="grid_container">
            <?php
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
            $connexion = null;
            ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2023 Quiz Night. Tous droits réservés.</p>
    </footer>
</body>

</html>