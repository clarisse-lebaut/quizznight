<?php
session_start();

// Vérification des autorisations d'accès
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Vérification de l'existence de l'identifiant de quiz dans l'URL
if (!isset($_GET['id'])) {
    echo "ID de quiz non spécifié dans l'URL.";
    exit();
}

// Récupération de l'identifiant du quiz depuis l'URL
$quiz_id = $_GET['id'];

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nightquiz";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Vérification de l'existence du quiz dans la base de données
$sql_quiz = "SELECT * FROM quiz WHERE id = :quiz_id";
$stmt_quiz = $conn->prepare($sql_quiz);
$stmt_quiz->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
$stmt_quiz->execute();

if ($stmt_quiz->rowCount() == 0) {
    echo "Aucun quiz trouvé avec cet ID.";
    exit();
}

// Récupération des informations sur le quiz
$row_quiz = $stmt_quiz->fetch(PDO::FETCH_ASSOC);

// Récupération des questions associées au quiz
$sql_questions = "SELECT * FROM questions WHERE quiz_id = :quiz_id";
$stmt_questions = $conn->prepare($sql_questions);
$stmt_questions->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
$stmt_questions->execute();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - <?php echo htmlspecialchars($row_quiz['title']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="admin.php">Administration</a></li>
                <li><a href="create_user.php">Créer un utilisateur</a></li>
                <li><a href="add_answers.php">Ajouter des réponses</a></li>
                <li><a href="add_questions.php">Ajouter des questions</a></li>
                <li><a href="?logout=true">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Quiz - <?php echo htmlspecialchars($row_quiz['title']); ?></h1>
        <p><?php echo htmlspecialchars($row_quiz['description']); ?></p>
        
        <?php if($stmt_questions->rowCount() == 0): ?>
            <p>Aucune question trouvée pour ce quiz.</p>
        <?php else: ?>
            <?php $i = 1; ?>
            <?php while($row_questions = $stmt_questions->fetch(PDO::FETCH_ASSOC)): ?>
                <h3>Question <?php echo $i; ?></h3>
                <p><?php echo htmlspecialchars($row_questions['question']); ?></p>

                <?php 
                // Récupération des réponses associées à la question
                $sql_answers = "SELECT * FROM answers WHERE question_id = :question_id";
                $stmt_answers = $conn->prepare($sql_answers);
                $stmt_answers->bindParam(':question_id', $row_questions['id'], PDO::PARAM_INT);
                $stmt_answers->execute();
                ?>

                <ul>
                    <?php while($row_answers = $stmt_answers->fetch(PDO::FETCH_ASSOC)): ?>
                        <li><?php echo htmlspecialchars($row_answers['answer']); ?></li>
                    <?php endwhile; ?>
                </ul>

                <?php $i++; ?>
            <?php endwhile; ?>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2023 Quiz Night. Tous droits réservés.</p>
    </footer>
</body>
</html>
