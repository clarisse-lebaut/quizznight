<?php
// Include files instantiate it
require '../config/config.php';
require '../class/classConnectDB.php';
$dbConnection = new ConnectToDatabase();
$connexion = $dbConnection->getConnexion();

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
$questions = $stmt_questions->fetchAll(PDO::FETCH_ASSOC); // Récupérer toutes les questions

// Use Dompdf
require_once '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Function to generate the PDF
function createPDF($row_quiz, $questions)
{
    // Creation of a Dompdf instance with options
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true); // Activer le parseur HTML5

    $dompdf = new Dompdf($options);

    // HTML content to convert to PDF
    // // $html is a global variable to instantiate the HTML
    $html = '<html>

    <head>
        <title>' . htmlspecialchars($row_quiz['title']) . '</title>
    </head>

    <style>
        body{
            font-family: arial, sans-serif;
        }
        h1, h3, p{
            text-align:center;
        }
        .card_question{
            border: solid gray 1px;
            gap:20px;
            margin-right: 50px;
            margin_left:50px;
            maring-bottom:10px;
            margin-top:10px;
        }
    </style>

    <body>';

    $html .= '<h1> Quizz : ' . htmlspecialchars($row_quiz['title']) . '</h1>';
    $html .= '<p>' . htmlspecialchars($row_quiz['description']) . '</p>';

    // Add questions and responses
    foreach ($questions as $question) {
        $dbConnection = new ConnectToDatabase();
        $connexion = $dbConnection->getConnexion();

        $html .= '<div class="card_question">';
        $html .= '<h3>' . htmlspecialchars($question['question_text']) . '</h3>';

        // Get the responses
        $sql_answers = "SELECT * FROM answer WHERE question_id = :question_id";
        $stmt_answers = $connexion->prepare($sql_answers);
        $stmt_answers->bindParam(':question_id', $question['id'], PDO::PARAM_INT);
        $stmt_answers->execute();
        $answers = $stmt_answers->fetchAll(PDO::FETCH_ASSOC);

        foreach ($answers as $answer) {
            $html .= '<p>' . htmlspecialchars($answer['answer_text']) . '</p>';
        }
        // Close the question card
        $html .= '</div>'; 
    }

    // Close the HTML part
    $html .= '</body></html>';

    // Load HTML content into Dompdf
    $dompdf->loadHtml($html);

    // Set PDF rendering options
    $dompdf->setPaper('A4', 'portrait'); // A4, portrait

    // Generate PDF
    $dompdf->render();

    // Displaying the PDF in the browser so you can download it
    $dompdf->stream('document.pdf', array('Attachment' => 0));
}

// Check if the form was submitted to generate the PDF
if (isset($_POST['generate_pdf'])) {
    // Calling the function to generate the PDF
    createPDF($row_quiz, $questions);
}


