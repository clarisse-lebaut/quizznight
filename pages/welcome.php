<?php
session_start();
require '../class/classConnect.php';
require '../class/classNavConnect.php';
require '../class/classQuizz.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: connexion.php");
    exit();
}

// Les instances
$dbConnection = new ConnectToDatabase();
$connexion = $dbConnection->getConnexion();
$navBar = new NavConnect();
$newQuiz = new createQuizz();

// Traitement de la création d'un nouveau quizz
$newQuiz->createNewQuizz($connexion); // Appel de la méthode pour créer un nouveau quizz
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue !</title>
</head>

<body>
    <style>
        body {
            font-family: arial, sans-serif;
        }

        h2 {
            text-align: center;
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
    <?php
    $navBar->NavConnect();
    ?>
    <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
    <p>Crée un quizz</p>
    <hr width="500px">
    <div class="box">
        <form action="" method="POST">
            <div id="details">
                <input id="title" type="text" name="quizTitle" placeholder="Titre du quizz" required>
                <input id="plot" type="text" name="description_quizz" placeholder="Présenter votre quizz" required>
            </div>
            <input id="card_quizz" type="submit" name="newQuizz" value="Et maintenant le contenue">
        </form>
    </div>
    <br>
    <br>
    <p>Vos quizz</p>
    <hr width="500px">
    <div class="all_quizz">
        <?php
        $user_id = $_SESSION['user_id']; // Récupérer l'ID de l'utilisateur depuis la session
        // Préparer la requête SQL pour récupérer tous les quizz créés par l'utilisateur
        $stmt2 = $connexion->prepare("SELECT * FROM quiz WHERE creator_id = :user_id");
        // Lier le paramètre :user_id à la variable $user_id
        $stmt2->bindParam(':user_id', $user_id);
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
    </div>
</body>

</html>