<?php
// Inclure le fichier de configuration
require '../config/config.php';
require '../class/classConnectDB.php';

// Connexion à la base de données
$dbConnection = new ConnectToDatabase();
$connexion = $dbConnection->getConnexion();

// Vérification des autorisations d'accès
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// Vérification de l'existence de l'identifiant de quiz dans l'URL
if (!isset($_GET['id'])) {
    echo "ID de quiz non spécifié dans l'URL.";
    exit();
}

// Récupération de l'identifiant du quiz depuis l'URL
$quiz_id = $_GET['id'];

// Vérification de l'existence du quiz dans la base de données
$sql_quiz = "SELECT * FROM quiz WHERE id = :quiz_id";
$stmt_quiz = $connexion->prepare($sql_quiz);
$stmt_quiz->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
$stmt_quiz->execute();

if ($stmt_quiz->rowCount() == 0) {
    echo "Aucun quiz trouvé avec cet ID.";
    exit();
}

// Récupération des informations sur le quiz
$row_quiz = $stmt_quiz->fetch(PDO::FETCH_ASSOC);

// Récupération des questions associées au quiz
$sql_questions = "SELECT * FROM question WHERE quiz_id = :quiz_id";
$stmt_questions = $connexion->prepare($sql_questions);
$stmt_questions->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
$stmt_questions->execute();
$questions = $stmt_questions->fetchAll(PDO::FETCH_ASSOC); // Récupérer toutes les questions

// Utilisation de Dompdf
require_once '../vendor/autoload.php'; // Inclure l'autoloader de Composer
use Dompdf\Dompdf;
use Dompdf\Options;

// Fonction pour générer le PDF
function createPDF($row_quiz, $questions)
{
    // Création d'une instance de Dompdf avec des options
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true); // Activer le parseur HTML5

    $dompdf = new Dompdf($options);

    // Contenu HTML que vous souhaitez convertir en PDF
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

    // Ajouter les questions et potentiellement les réponses
    foreach ($questions as $question) {
        $dbConnection = new ConnectToDatabase();
        $connexion = $dbConnection->getConnexion();

        $html .= '<div class="card_question">';
        $html .= '<h3>' . htmlspecialchars($question['question_text']) . '</h3>';

        // Récupération des réponses pour cette question (s'il y a lieu)
        $sql_answers = "SELECT * FROM answer WHERE question_id = :question_id";
        $stmt_answers = $connexion->prepare($sql_answers);
        $stmt_answers->bindParam(':question_id', $question['id'], PDO::PARAM_INT);
        $stmt_answers->execute();
        $answers = $stmt_answers->fetchAll(PDO::FETCH_ASSOC);

        foreach ($answers as $answer) {
            $html .= '<p>' . htmlspecialchars($answer['answer_text']) . '</p>';
        }
        $html .= '</div>'; // Fermeture de la carte de question
    }

    $html .= '</body></html>';

    // Charger le contenu HTML dans Dompdf
    $dompdf->loadHtml($html);

    // (Optionnel) Définir les options de rendu PDF (taille de papier, etc.)
    $dompdf->setPaper('A4', 'portrait'); // Format A4, mode portrait

    // Rendu du PDF (génération)
    $dompdf->render();

    // Affichage du PDF dans le navigateur ou téléchargement
    $dompdf->stream('document.pdf', array('Attachment' => 0));
}

// Vérifier si le formulaire a été soumis pour générer le PDF
if (isset($_POST['generate_pdf'])) {
    createPDF($row_quiz, $questions); // Appel de la fonction pour générer le PDF
}


