
<?php
session_start();
require '../class/classUser.php';
require '../class/classConnect.php';

// Vérifie soumission du formulaire
if ($_POST && !isset($_POST['logout'])) {
    // Récupère les données du formulaire
    $user_name = $_POST['username'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];

    // Crée une instance de la classe de connexion
    $dbConnection = new ConnectToDatabase();
    $connexion = $dbConnection->getConnexion();

    // Crée une instance de la classe User en passant la connexion PDO
    $user = new User($connexion);

    // Tentative de création de l'utilisateur
    $result = $user->createUser($user_name, $user_email, $user_password);

    if ($result === true) {
        // Stocker les informations de l'utilisateur dans la session
        $_SESSION['username'] = $user_name;

        // Rediriger vers la page de bienvenue après l'inscription
        header("Location: welcome.php");
        exit();
    } else {
        $message = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizNight</title>
</head>
<body>
        <style>
        body{
            background-color:#F8F8FF;
            display: flex;
            flex-direction : column;
            justify-content : center;
            align-items : center;
            height : 95vh;
            font-family: arial;
        }

        .box{
            background-color:white;
            box-shadow: 0 0 30px 1px #DCDCDC;
            padding: 50px 100px 50px 100px;
            display:flex;
            flex-direction:column;
            text-align:center;
            border-radius:20px;
        }

        .label{
            margin:20px;
        }
        
        input{
            height:30px;
            width:250px;
        }

        button{
            padding:15px;
            border:none;
            background-color:#F8F8FF;
            border-radius:10px;
            box-shadow: #DCDCDC 0 0 5px 1px;     
        }

        button:active {
            transform: translateY(10px); /* Correction ici */
        }

        a{
            text-decoration:none;
            color:blue;
        }

        a:hover {
            color:green;
        }

    </style>
    <div class="box">
        <h2>SE CREER UN COMPTE</h2>
        <form action="" method="POST">
            <div class="label">
                <label for="username">Nom d'utilisateur</label>
            </div>
            <input type="text" id="username" name="username" required><br><br>
            
            <div class="label">
                <label for="password">Mot de passe</label>
            </div>
            <input type="password" id="password" name="password" required><br><br>
            
            <div class="label">
                <label for="email">Email</label>
            </div>
            <input type="email" id="email" name="email" required><br><br>
            
            <button type="submit">Confirmer</button>
        </form>
        <br>
        <br>
        <a href="connexion.php">Déjà un compte ? Se connecter</a>
        <br>
        <a href="../index.php">Retour à la page d'accueil</a>
        </div>
</body>
</html>
