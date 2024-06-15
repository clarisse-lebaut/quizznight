<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <main class="main">
        <?php
        class NavConnect
        {
            public function NavConnect()
            {
                echo "<ul>";
                echo "<li><a class='a_style' href='../pages/welcome.php'>Accueil</a></li>";
                echo "<li><a class='a_style' href='../pages/create_quiz.php'>Créer un quiz</a></li>";
                echo "<li><a class='a_style' href='../pages/add_questions.php'>Ajouter des questions</a></li>";
                echo "<li><a class='a_style' href='../pages/add_answers.php'>Ajouter des réponses</a></li>";
                echo "<li><a class='a_style' href='../pages/admin.php'>Admin</a></li>";
                echo "<li><a class='a_style' href='../config/disconnect.php'>Déconnexion</a></li>";
                echo "</ul>";
            }
        }
        ?>
    </main>
</body>

</html>