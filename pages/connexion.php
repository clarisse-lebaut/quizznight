<?php
session_start();
require '../class/classConnect.php';
require '../class/classUser.php';

// Initialise la connexion à la base de données
$db = new ConnectToDatabase();
$connexion = $db->getConnexion();

// Vérifie si le formulaire a été soumis
if ($_POST) {
    // Récupère les données du formulaire
    $user_name = $_POST['username'];
    $user_password = $_POST['password'];

    // Requête SQL pour vérifier les informations de connexion de l'utilisateur
    $sql = "SELECT * FROM user WHERE username = :username";

    try {
        // Préparer la requête
        $stmt = $connexion->prepare($sql);
        // Lier les paramètres
        $stmt->bindParam(':username', $user_name);
        // Exécuter la requête
        $stmt->execute();

        // Récupérer les résultats
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($user_password, $user['password'])) {
            // Les informations de connexion sont correctes
            $_SESSION['username'] = $user['username'];
            // Ajouter cette ligne pour stocker l'ID de l'utilisateur dans la session
            $_SESSION['user_id'] = $user['id']; // Assurez-vous que le champ dans votre table est nommé 'id'
            // Rediriger vers une page de bienvenue après la connexion
            header("Location: welcome.php");
            exit();
        } else {
            $message = "Nom d'utilisateur ou mot de passe incorrect";
        }
    } catch (PDOException $e) {
        $message = "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion
    $connexion = null;
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
        <h2>CONNEXION</h2>
        <form action="" method="post">
            <div class="label">
                <label for="username">Nom d'utilisateur</label>
            </div>
            <input type="text" id="username" name="username" required><br><br>
            
            <div class="label">
                <label for="password">Mot de passe</label>
            </div>
            <input type="password" id="password" name="password" required><br><br>
            
            <button type="submit">Se connecter</button>
        </form>
        <br>
        <br>
        <a href="created.php">Nouveau ? Créez vous un compte</a>
        <br>
        <a href="../index.php">Retour à la page d'accueil</a>
    </div>
</body>
</html>
