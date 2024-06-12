<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nightquiz";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: index.php");
    exit();
}

$quiz_id = $_GET["id"];

// Récupérer les informations du quiz depuis la base de données
$sql = "SELECT title, description FROM quizzes WHERE id = :quiz_id AND created_by = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':quiz_id', $quiz_id);
$stmt->bindParam(':user_id', $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    header("Location: admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];

    // Mettre à jour le quiz dans la base de données
    $sql = "UPDATE quizzes SET title = :title, description = :description WHERE id = :quiz_id AND created_by = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':quiz_id', $quiz_id);
    $stmt->bindParam(':user_id', $_SESSION["user_id"]);
    $stmt->execute();

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Quiz</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Edit Quiz</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $quiz_id); ?>">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo $result["title"]; ?>" required><br><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo $result["description"]; ?></textarea><br><br>
        <input type="submit" value="Update Quiz">
    </form>
</body>
</html>
