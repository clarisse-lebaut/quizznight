<?php
require './class/classConnect.php';
require "./class/classNavbar.php";
// Les instances
$navBar = new NavBar();
$dbConnection = new ConnectToDatabase();
$connexion = $dbConnection->getConnexion();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
    body {
        font-family: arial, sans-serif;
    }

    #card_quizz {
        background-color: #F8F8FF;
        box-shadow: #DCDCDC 0 0 5px 1px;
        display: flex;
        gap: 30px;
        align-items: center;
        justify-content: center;
        width: 250px;
        height: 25vh;
        margin: auto;
        border: none;
        border-radius: 10px;
        font-size: 20px;
    }

    #card_quizz:active {
        transform: translateY(10px);
    }

    #details {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 250px;
        margin: auto;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .all_quizz {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
        justify-content: center;
        align-items: center;
        margin: 40px 50px 20px 50px;
    }

    .cards {
        border-radius: 10px;
        border: #DCDCDC solid 1px;
        box-shadow: #DCDCDC 0 0 10px 1px;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 10px;
    }

    #box_title {
        background-color: blue;
        width: 100%;
        height: 100%;
        border-radius: 5px;
    }
</style>

<body>
    <?php
    $navBar->Navbar();
    ?>
    <h1>QuizNight</h1>
    <br>
    <p>Choisssez en un et c'est partie !</p>
    <hr width="250px">
</body>

</html>

<div class="all_quizz">
    <?php
    $stmt2 = $connexion->prepare("SELECT * FROM quiz");
    // Exécuter la requête
    $stmt2->execute();
    // Récupérer les résultats
    $quizzes = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    // Vérifier s'il y a des quizz enregistrés
    if (isset($quizzes)) {
        // Parcourir les quizz récupérés et les afficher
        foreach ($quizzes as $quiz) {
            echo "<form method='POST'>";
            echo "<div class='cards'>";
            echo "<div id='box_title'><p>" . htmlspecialchars($quiz['title']) . "</p></div>";
            echo "<p id='box_plot'>" . htmlspecialchars($quiz['description']) . "</p>";
            echo "<a class='a-style' href='./quizz.php'>Consulter</a><br>";
            echo "<button name='download' type='submit'>Telécharger</button>";
            echo "</div>";
            echo "</form>";
        }
    } else {
        echo "<p>Aucun quizz trouvé.</p>";
    }
    ?>