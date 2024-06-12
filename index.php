<?php
    require "./class/classNavbar.php";
    // Créez une instance de la classe NavBar
    $navBar = new NavBar();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $navBar->Navbar();
    ?>
    <h1>QuizNight</h1>
    <br>
    <p>Faire appraitre tous les quizz de la base de donnée ici</p>
</body>
</html>