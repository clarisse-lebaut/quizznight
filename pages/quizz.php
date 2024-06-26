<?php
// Include files and instantiate it
require '../config/config.php';
require '../class/classConnectDB.php';
$dbConnection = new ConnectToDatabase();
$connexion = $dbConnection->getConnexion();
require '../class/classNavBar.php';
$navBar = new NavConnect();
require '../class/classFooter.php';
$footer = new Footer();


// Checking access permissions
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// Checking for existence of quiz id in URL
if (!isset($_GET['id'])) {
    echo "ID de quiz non spécifié dans l'URL.";
    exit();
}

// Retrieving the quiz ID from the URL
$quiz_id = $_GET['id'];

// Checking the existence of the quiz in the database
$sql_quiz = "SELECT * FROM quiz WHERE id = :quiz_id";
$stmt_quiz = $connexion->prepare($sql_quiz);
$stmt_quiz->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
$stmt_quiz->execute();

if ($stmt_quiz->rowCount() == 0) {
    echo "Aucun quiz trouvé avec cet ID.";
    exit();
}

// Retrieving quiz information
$row_quiz = $stmt_quiz->fetch(PDO::FETCH_ASSOC);

// Retrieving questions associated with the quiz
$sql_questions = "SELECT * FROM question WHERE quiz_id = :quiz_id";
$stmt_questions = $connexion->prepare($sql_questions);
$stmt_questions->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
$stmt_questions->execute();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - <?php echo htmlspecialchars($row_quiz['title']); ?></title>
    <link rel="stylesheet" href="../styles/navbar.css">
    <link rel="stylesheet" href="../styles/body.css">
    <link rel="stylesheet" href="../styles/welcome.css">
    <link rel="stylesheet" href="../styles/quizz(2).css">
</head>

<body>
    <header>
        <nav class="navbar">
            <?php $navBar->NavConnect(); ?>
        </nav>
    </header>

    <main>
        <h1>Quiz - <?php echo htmlspecialchars($row_quiz['title']); ?></h1>
        <p><?php echo htmlspecialchars($row_quiz['description']); ?></p>

        <?php
        function isLoggedIn()
        {
            return isset($_SESSION['user_id']);
        }
        if (isLoggedIn()) {
            echo "<form id='PDF' action='generate_pdf.php?id=" . htmlspecialchars($quiz_id) . "' method='POST'>";
            echo "<button id='btn_PDF' type='submit' name='generate_pdf'>Télécharger le PDF</button>";
            echo "</form>";
        }
        ?>

        <!-- Slider container -->
        <div class="slider-container">
            <div class="slider-wrapper">
                <!-- Slides -->
                <?php if ($stmt_questions->rowCount() == 0): ?>
                    <div class="slide">
                        <p>Aucune question trouvée pour ce quiz.</p>
                    </div>
                <?php else: ?>
                    <?php $i = 1; ?>
                    <?php while ($row_questions = $stmt_questions->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="slide">
                            <h3>Question <?php echo $i; ?></h3>
                            <p><?php echo htmlspecialchars($row_questions['question_text']); ?></p>

                            

                            <?php $i++; ?>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

            <!-- Navigation buttons -->
            <button class="slider-button-prev" onclick="moveSlide(-1)">&#10094;</button>
            <button class="slider-button-next" onclick="moveSlide(1)">&#10095;</button>
        </div>
    </main>
    <!-- Part to make appear the quizz in a carroussel -->
    <script>
        let currentSlide = 0;

        function moveSlide(direction) {
            const slides = document.querySelectorAll('.slide');
            const totalSlides = slides.length;
            currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
            const offset = -currentSlide * 100;
            document.querySelector('.slider-wrapper').style.transform = `translateX(${offset}%)`;
        }

        function toggleAnswer(questionId) {
            const answerContainer = document.getElementById('answer-container-' + questionId);
            if (answerContainer.style.display === 'none') {
                answerContainer.style.display = 'block';
            } else {
                answerContainer.style.display = 'none';
            }
        }

        // Initialize first slide
        moveSlide(0);
    </script>


    <footer>
        <?php $footer->footer(); ?>
    </footer>
</body>

</html>