<?php
// create_user.php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nightquiz";

// Traitement du formulaire lorsque soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        header("Location: index.php"); // Vous pouvez modifier cette URL selon vos besoins
        exit();
    } catch(PDOException $e) {
        echo "Erreur: " . $e->getMessage(); // Gestion des erreurs de connexion à la base de données
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Créer un utilisateur - Quiz Night</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="login.php">Connexion</a></li>
        </ul>
    </nav>
</header>

<main>
    <h1>Créer un utilisateur</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="role">Rôle:</label>
        <select id="role" name="role">
            <option value="user">Utilisateur</option>
            <option value="admin">Administrateur</option>
        </select>

        <input type="submit" name="submit" value="Créer l'utilisateur" class="btn">
    </form>
</main>

<footer>
    <p>&copy; 2023 Quiz Night. Tous droits réservés.</p>
</footer>
</body>
</html>
