<?php
// Include files instantiate it
require '../config/config.php';
require '../class/classNavBar.php';
$navBar = new NavConnect();
require '../class/classFooter.php';
$footer = new Footer();

// Checking access permissions
if (!isset($_SESSION["user_id"]) || $_SESSION["roles"] !== "admin") {
    header("Location: ./welcome.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiznight";

$messageConfirmed = ""; 

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_POST) {
        if (isset($_POST["delete"])) {
            // Handle delete user
            $user_id = $_POST["user_id"];
            $sql = "DELETE FROM user WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            // Redirect back to admin page after deletion
            header("Location: ./admin.php");
            exit();
        } else {
            // Handle update user
            $user_id = $_POST["user_id"];
            $username = $_POST["username"];
            $email = $_POST["email"];
            $roles = $_POST["roles"];

            $sql = "UPDATE user SET username = :username, email = :email, roles = :roles WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':roles', $roles, PDO::PARAM_STR);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            // Re-fetch the updated user to display in the form
            $sql = "SELECT id, username, email, roles FROM user WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $messageConfirmed = "Utilisateur mis a jour avec succès !";
        }
    } else {
        $user_id = $_GET["id"];
        $sql = "SELECT id, username, email, roles FROM user WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="../styles/body.css">
    <link rel="stylesheet" href="../styles/navbar.css">
    <link rel="stylesheet" href="../styles/edit.css">
</head>

<header>
    <nav class="navbar">
        <?php
        $navBar->NavConnect();
        ?>
    </nav>
</header>

<body>
    <main>
        <h1>Editer l'utilisateur : <?php echo htmlspecialchars($user['username']); ?></h1>

        <form method="POST" action="edit_user.php">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <div id="container_one">
                <div id="box">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"
                        value="<?php echo htmlspecialchars($user['username']); ?>" required><br><br>
                </div>
                <div id="box">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                        required><br><br>
                </div>
                <div id="box">
                    <label for="roles">Roles</label>
                    <input type="text" id="roles" name="roles" value="<?php echo htmlspecialchars($user['roles']); ?>"
                        required><br><br>
                </div>
            </div>
            <div id="container_two">
                <button class="btn_one" type="submit">Mettre à jour</button>
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                <button class="btn_two" type="submit" name="delete"
                    onclick="return confirm('Are you sure you want to delete this user?');">Supprimer</button>
            </div>
        </form>
        <p class="msg"><?php echo $messageConfirmed ?></p>
        <a href="./admin.php">Retour sur la page adminstrateur</a>
    </main>
    <footer>
        <?php
        $footer->footer();
        ?>
    </footer>
</body>

</html>