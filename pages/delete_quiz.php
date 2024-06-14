<?php
require '../config.php'; // Inclure la classe de connexion à la base de données

if (!isset($_SESSION["user_id"]) || $_SESSION["roles"] != "admin") {
    header("Location: ./index.php");
    exit();
}

require '../class/classConnectDB.php'; // Inclure la classe de connexion à la base de données

try {
    $dbConnection = new ConnectToDatabase();
    $conn = $dbConnection->getConnexion();

    if (!isset($_GET["id"])) {
        header("Location: ./admin.php");
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
        header("Location: ./admin.php");
        exit();
    }

    // Delete the quiz from the database
    $sql = "DELETE FROM quiz WHERE id = :quiz_id AND creator_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
    $stmt->execute();

    header("Location: ./admin.php");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
