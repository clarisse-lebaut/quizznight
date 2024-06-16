<?php
// Include files and instantiate it
require '../config/config.php';
require '../class/classNavBar.php';
$navBar = new NavConnect();
require '../class/classFooter.php';
$footer = new Footer();

// Checking access permissions
if (!isset($_SESSION["user_id"]) || $_SESSION["roles"] !== "admin") {
    header("Location: ./welcome.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiznight";

$messageUpdate = "";
$messageDeleted = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_POST) {
        if (isset($_POST["delete"])) {
            // Handle delete answer
            $answer_id = $_POST["answer_id"];
            $sql = "DELETE FROM answer WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $answer_id, PDO::PARAM_INT);
            $stmt->execute();

            $messageDeleted = "Réponse supprimée avec succès !";

            header("Location: admin.php"); // Redirect back to admin page after deletion
            exit();
        } else {
            // Handle update answer
            $answer_id = $_POST["answer_id"];
            $answer_text = $_POST["answer_text"];

            $sql = "UPDATE answer SET answer_text = :answer_text WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':answer_text', $answer_text, PDO::PARAM_STR);
            $stmt->bindParam(':id', $answer_id, PDO::PARAM_INT);
            $stmt->execute();

            $messageUpdate = "Réponse mise à jour réussi avec succès !";

            // Re-fetch the updated answer to display in the form
            $sql = "SELECT id, answer_text FROM answer WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $answer_id, PDO::PARAM_INT);
            $stmt->execute();
            $answer = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } else {
        $answer_id = $_GET["id"];
        $sql = "SELECT id, answer_text FROM answer WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $answer_id, PDO::PARAM_INT);
        $stmt->execute();
        $answer = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Answer</title>
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
        <h1>Editer la réponse du Quiz : <?php echo htmlspecialchars($answer['id']); ?></h1>

        <form method="POST" action="edit_answer.php">
            <input type="hidden" name="answer_id" value="<?php echo htmlspecialchars($answer['id']); ?>">
            <div id="container_one">
                <div id="box">
                    <label for="answer_text">Answer:</label>
                    <input type="text" id="answer_text" name="answer_text"
                        value="<?php echo htmlspecialchars($answer['answer_text']); ?>" required><br><br>
                </div>
            </div>
            <div id="container_two">
                <button class="btn_one" type="submit">Mettre à jour</button>
                <input type="hidden" name="answer_id" value="<?php echo htmlspecialchars($answer['id']); ?>">
                <button class="btn_two" type="submit" name="delete"
                    onclick="return confirm('Are you sure you want to delete this answer?');">Supprimer</button>
            </div>
        </form>

        <p class="msg"><?php echo $messageUpdate ?></p>
        <p class="msg"><?php echo $messageDeleted ?></p>
        <a href="./admin.php">Retour sur la page adminstrateur</a>
    </main>
    <footer>
        <?php
        $footer->footer();
        ?>
    </footer>
</body>

</html>