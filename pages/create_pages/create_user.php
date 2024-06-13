<?php
// create_user.php

session_start();
// Params to be connect ad the Database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiznight";

// Traitement du formulaire lorsque soumis
if ($_POST) {
    try {
        // Connexion à la base de données
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupération des données du formulaire
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $role = $_POST["role"];

        // Hachage du mot de passe
        $hashed_password = hash('sha256', $password);

        // Préparation et exécution de la requête SQL pour insérer le nouvel utilisateur dans la base de données
        $sql = "INSERT INTO user (username, password, email, roles) VALUES (:username, :password, :email, :roles)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':roles', $role);
        $stmt->execute();

        // Redirection vers une page appropriée après la création de l'utilisateur
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage(); // Gestion des erreurs de connexion à la base de données
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Créer un utilisateur - Quiz Night</title>
    <link rel="stylesheet" href="../../styles/log.css">
</head>

<body>
    <main>
        <h1>SE CREER UN COMTPE</h1>
        <form class="box" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label class="label" for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required>

            <label class="label" for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label class="label" for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>


            <label class="label" for="role">Rôle</label>
            <select id="role" name="role">
                <option value="user">Utilisateur</option>
                <option value="admin">Administrateur</option>
            </select>
            <button type="submit" name="submit">Crée un compte</button>
            <br>
            <br>
            <a href="../../index.php">Accueil</a>
            <br>
            <a href="../login.php">Connexion</a>
            <p>&copy; 2023 Quiz Night. Tous droits réservés.</p>
        </form>
    </main>
</body>

</html>