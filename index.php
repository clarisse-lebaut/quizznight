<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <style>
        nav{
            background-color:yellow;
            display:flex;
        }

        a{
            margin:20px;
        }

        h1, p{
            text-align:center;
        }

        #container{
            display : flex;
            gap:20px;
        }

        #box{
            width:150px;
            margin : 50px;
            text-align:center;
        }
    </style>
    <nav>
        <ul>
            <a href="./pages/connexion.php">Se connecter</a>
            <a href="./pages/created.php">Se créer un compte</a>
        </ul>
    </nav>
    <h1>QuizNight</h1>
    <br>
    <p>Faire appraitre tous les quizz de la base de donnée ici</p>
</body>
</html>