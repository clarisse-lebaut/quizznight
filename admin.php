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
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

// Fetch quizzes
$sql_quizzes = "SELECT id, title, description, creator_id FROM quiz";
$stmt_quizzes = $conn->query($sql_quizzes);
$quizzes = $stmt_quizzes->fetchAll(PDO::FETCH_ASSOC);

// Fetch questions
$sql_questions = "SELECT q.id, q.question_text, q.quiz_id, z.title AS quiz_title FROM question q JOIN quiz z ON q.quiz_id = z.id";
$stmt_questions = $conn->query($sql_questions);
$questions = $stmt_questions->fetchAll(PDO::FETCH_ASSOC);

// Fetch answers
$sql_answers = "SELECT a.id, a.answer_text, q.question_text, z.title AS quiz_title FROM answer a JOIN question q ON a.question_id = q.id JOIN quiz z ON q.quiz_id = z.id";
$stmt_answers = $conn->query($sql_answers);
$answers = $stmt_answers->fetchAll(PDO::FETCH_ASSOC);

// Fetch users
$sql_users = "SELECT id, username, email, roles FROM user";
$stmt_users = $conn->query($sql_users);
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
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

    <!-- Quizzes -->
    <h2>Quizzes</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Created By</th>
            <th>Action</th>
        </tr>
        <?php foreach ($quizzes as $quiz): ?>
            <tr>
                <td><?php echo htmlspecialchars($quiz['title']); ?></td>
                <td><?php echo htmlspecialchars($quiz['description']); ?></td>
                <td><?php echo htmlspecialchars($quiz['creator_id']); ?></td>
                <td><a href="edit_quiz.php?id=<?php echo htmlspecialchars($quiz['id']); ?>">Edit</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Questions -->
    <h2>Questions</h2>
    <table>
        <tr>
            <th>Question</th>
            <th>Quiz</th>
            <th>Action</th>
        </tr>
        <?php foreach ($questions as $question): ?>
            <tr>
                <td><?php echo htmlspecialchars($question['question_text']); ?></td>
                <td><?php echo htmlspecialchars($question['quiz_title']); ?></td>
                <td><a href="edit_question.php?id=<?php echo htmlspecialchars($question['id']); ?>">Edit</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Answers -->
    <h2>Answers</h2>
    <table>
        <tr>
            <th>Answer</th>
            <th>Question</th>
            <th>Quiz</th>
            <th>Action</th>
        </tr>
        <?php foreach ($answers as $answer): ?>
            <tr>
                <td><?php echo htmlspecialchars($answer['answer_text']); ?></td>
                <td><?php echo htmlspecialchars($answer['question_text']); ?></td>
                <td><?php echo htmlspecialchars($answer['quiz_title']); ?></td>
                <td><a href="edit_answer.php?id=<?php echo htmlspecialchars($answer['id']); ?>">Edit</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Users -->
    <h2>Users</h2>
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['roles']); ?></td>
                <td><a href="edit_user.php?id=<?php echo htmlspecialchars($user['id']); ?>">Edit</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>

<footer>
    <p>&copy; 2023 Quiz Night. Tous droits réservés.</p>
</footer>
</body>
</html>

<?php
// Close the database connection
$conn = null;
?>
