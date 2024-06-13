<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["roles"] !== "admin") {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nightquiz";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["delete"])) {
            // Handle delete answer
            $answer_id = $_POST["answer_id"];
            $sql = "DELETE FROM answer WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $answer_id, PDO::PARAM_INT);
            $stmt->execute();
            echo "Answer deleted successfully!";
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

            echo "Answer updated successfully!";
            
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
    <link rel="stylesheet" href="styles.css">
</head>
   
<header> <nav>
        <ul><li><a href="index.php">Retour à l'index</a></li><li><a href="admin.php">Administration</a></li></header>
</ul></nav>
<body>
    <h1>Edit Answer</h1>
    <form method="post" action="edit_answer.php">
        <input type="hidden" name="answer_id" value="<?php echo htmlspecialchars($answer['id']); ?>">
        <label for="answer_text">Answer:</label>
        <input type="text" id="answer_text" name="answer_text" value="<?php echo htmlspecialchars($answer['answer_text']); ?>" required><br><br>
        <input type="submit" value="Update Answer">
    </form>
    <form method="post" action="delete_answer.php" style="margin-top: 20px;">
        <input type="hidden" name="answer_id" value="<?php echo htmlspecialchars($answer['id']); ?>">
        <input type="submit" name="delete" value="Delete Answer" onclick="return confirm('Are you sure you want to delete this answer?');">
    </form>
</body>
</html>