<?php
require '../../config/config.php'; // Inclure le fichier de configuration
require '../../class/classUser.php';
$createUser = new User($dbConnection);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Créer un utilisateur - Quiz Night</title>
    <link rel="stylesheet" href="../../styles/log.css">
</head>

<body>
    <main>
        <h1>SE CRÉER UN COMPTE</h1>
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
            <button type="submit" name="submit">Créer un compte</button>
            <br><br>
            <a href="../../index.php">Accueil</a>
            <br>
            <a href="../login.php">Connexion</a>
            <p>&copy; 2023 Quiz Night. Tous droits réservés.</p>
        </form>
    </main>
</body>

</html>