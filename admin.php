<?php
// admin.php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "new_quiznight";  // Mise à jour ici

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["title"]) && isset($_POST["description"])) {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $created_by = $_SESSION["user_id"];

    $sql = "INSERT INTO quiz (title, description, created_by) VALUES (:title, :description, :created_by)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':created_by', $created_by);
    $stmt->execute();

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administration - Quiz Night</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="admin.php">Administration</a></li>
            <li><a href="?logout=true">Déconnexion</a></li>
        </ul>
    </nav>
</header>

<main>
    <h1>Administration</h1>

    <!-- Formulaire de création de quiz -->
    <h2>Créer un nouveau quiz</h2>
    <form method="POST" action="admin.php">
        <label for="title">Titre:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <input type="submit" value="Créer le quiz" class="btn">
    </form>

    <!-- Liste des quiz existants -->
    <h2>Quiz existants</h2>
    <ul>
        <?php
        // Récupérer la liste des quiz créés par l'administrateur connecté
        $sql = "SELECT id, title, description FROM quiz WHERE created_by = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            foreach ($result as $row) {
                echo "<li>";
                echo "<h3>" . htmlspecialchars($row["title"]) . "</h3>";
                echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
                echo "<a href='edit_quiz.php?id=" . $row["id"] . "' class='btn'>Modifier</a>";
                echo "<a href='delete_quiz.php?id=" . $row["id"] . "' class='btn'>Supprimer</a>";
                echo "</li>";
            }
        } else {
            echo "<p>Aucun quiz créé.</p>";
        }
        ?>
    </ul>
</main>

<footer>
    <p>&copy; 2023 Quiz Night. Tous droits réservés.</p>
</footer>
</body>
</html>

<?php
// Fermer la connexion à la base de données
$conn = null;
?>
