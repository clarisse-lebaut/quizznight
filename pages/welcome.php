<?php
// Include configuration file
require '../config/config.php';
// Include database connection file and instantiate it
require '../class/classConnectDB.php';
$dbConnection = new ConnectToDatabase();
$connexion = $dbConnection->getConnexion();
// Include navbar file and instantiate it
require '../class/classNavBar.php';
$navBar = new NavConnect();
// Include footer fil and instantiate it
require '../class/classFooter.php';
$footer = new Footer();

// Function to check if the user is connected
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Night</title>
    <link rel="stylesheet" href="../styles/navbar.css">
    <link rel="stylesheet" href="../styles/body.css">
    <link rel="stylesheet" href="../styles/welcome(2).css">
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

        <p class="title">Hey <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

        <br>
        <p class="title">Que souhaitez-vous faire ?</p>
        <hr width="250px">
        <br>

        <!-- Card with link to create and add elements -->
        <div class="card_link_container">
            <div class="card_link_box">
                <a class='a_card' href='../pages/create_quiz.php'>Créer un quiz</a>
            </div>
            <div class="card_link_box">
                <a class='a_card' href='../pages/add_questions.php'>Ajouter questions</a>
            </div>
            <div class="card_link_box">
                <a class='a_card' href='../pages/add_answers.php'>Ajouter réponses</a>
            </div>
        </div>

        <br><br>
        <p class="title">Dans quel domaine voulez-vous tester votre savoir ?</p>
        <hr width="250px">
        <br>

        <div class="grid_container">
            <?php
            // Request to select elements and informations from quiz
            $sql = "SELECT q.id, q.title, q.description, u.username AS creator_id FROM quiz q JOIN user u ON q.creator_id = u.id";
            $stmt = $connexion->query($sql);

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='quiz-item'>";
                    echo "<h2>" . htmlspecialchars($row["title"]) . "</h2>";
                    echo "<p class='quiz_description'>" . htmlspecialchars($row["description"]) . "</p>";
                    echo "<p>Créé par : " . (isset($row["creator_id"]) ? htmlspecialchars($row["creator_id"]) : 'N/A') . "</p>";
                    echo "<div class='a_card_link'>";
                    if (isLoggedIn()) {
                        echo "<a class='a_details btn' href='quizz.php?id=" . htmlspecialchars($row["id"]) . "'>Commencer le quiz</a>";
                    }
                    if (isLoggedIn() && $_SESSION["roles"] == "admin") {
                        echo "<a class='a_details btn' href='delete_quiz.php?id=" . htmlspecialchars($row["id"]) . "'>Supprimer le quiz</a>";
                    }
                    echo "</div>";
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
        <?php
        $footer->footer();
        ?>
    </footer>
</body>

</html>