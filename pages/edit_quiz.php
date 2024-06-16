<?php
// Include files instantiate it
require '../config/config.php';
require '../class/classNavBar.php';
$navBar = new NavConnect();
require '../class/classFooter.php';
$footer = new Footer();

if (!isset($_SESSION["user_id"]) || $_SESSION["roles"] !== "admin") {
    header("Location: ./welcome.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiznight";

$messageUpdate = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_POST) {
        if (isset($_POST["delete"])) {
            // Handle delete quiz
            $quiz_id = $_POST["quiz_id"];
            $sql = "DELETE FROM quiz WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $quiz_id, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: ./admin.php");
            exit();
        } else {
            // Handle update quiz
            $quiz_id = $_POST["quiz_id"];
            $title = $_POST["title"];
            $description = $_POST["description"];

            $sql = "UPDATE quiz SET title = :title, description = :description WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':id', $quiz_id, PDO::PARAM_INT);
            $stmt->execute();

            $messageUpdate = "Quiz mis à jour avec succès !";

            // Re-fetch the updated quiz to display in the form
            $sql = "SELECT id, title, description FROM quiz WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $quiz_id, PDO::PARAM_INT);
            $stmt->execute();
            $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } else {
        if (isset($_GET["id"])) {
            $quiz_id = $_GET["id"];
            $sql = "SELECT id, title, description FROM quiz WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $quiz_id, PDO::PARAM_INT);
            $stmt->execute();
            $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$quiz) {
                echo "Quiz not found.";
                exit();
            }
        } else {
            echo "No quiz ID provided.";
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Quiz</title>
    <link rel="stylesheet" href="../styles/body.css">
    <link rel="stylesheet" href="../styles/navbar.css">
    <link rel="stylesheet" href="../styles/edit.css">
</head>

<header>
    <nav class="navbar">
        <?php
        $navBar->NavConnect();
        ?>
    </nav>
</header>

<body>
    <main>
        <h1>Editer le quizz : <?php echo htmlspecialchars($quiz['title']); ?></h1>

        <?php if (isset($quiz)): ?>

            <form method="POST" action="edit_quiz.php">
                <input type="hidden" name="quiz_id" value="<?php echo htmlspecialchars($quiz['id']); ?>">
                <div id="container_one">
                    <div id="box">
                        <label for="title">Titre</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($quiz['title']); ?>"
                            required>
                        <label for="description">Description</label>
                        <textarea name="description"><?php echo htmlspecialchars($quiz['description']); ?></textarea>
                    </div>
                </div>
                <div id="container_two">
                    <button class="btn_one" type="submit">Mettre à jour</button>
                    <input type="hidden" name="quiz_id" value="<?php echo htmlspecialchars($quiz['id']); ?>">
                    <button class="btn_two" type="submit" name="delete"
                        onclick="return confirm('Are you sure you want to delete this quiz?');">Supprimer</button>
                </div>
            </form>

        <?php endif; ?>

        <p class="msg"><?php echo $messageUpdate ?></p>
        <a href="./admin.php">Retour sur la page adminstrateur</a>
    </main>
    <footer>
        <?php
        $footer->footer();
        ?>
    </footer>
</body>


</html>