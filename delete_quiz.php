<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["roles"] != "admin") {
    header("Location: index.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nightquiz";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_GET["id"])) {
        header("Location: admin.php");
        exit();
    }

    $quiz_id = $_GET["id"];

    // Verify if the quiz belongs to the logged-in admin
    $sql = "SELECT id FROM quiz WHERE id = :quiz_id AND creator_id  = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        header("Location: admin.php");
        exit();
    }

    // Delete the quiz from the database
    $sql = "DELETE FROM quiz WHERE id = :quiz_id AND creator_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
    $stmt->execute();

    header("Location: admin.php");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
