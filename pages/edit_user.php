<?php
require '../class/classNavBar.php';
$navBar = new NavConnect();
// Inclure le fichier de configuration
require '../config/config.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["roles"] !== "admin") {
    header("Location: ./welcome.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiznight";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["delete"])) {
            // Handle delete user
            $user_id = $_POST["user_id"];
            $sql = "DELETE FROM user WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            echo "User deleted successfully!";
            header("Location: ./admin.php"); // Redirect back to admin page after deletion
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

            echo "User updated successfully!";

            // Re-fetch the updated user to display in the form
            $sql = "SELECT id, username, email, roles FROM user WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="../styles/nav.css">
    <link rel="stylesheet" href="../styles/body.css">
</head>

<header>
    <nav class="navbar">
        <?php
        $navBar->NavConnect();
        ?>
    </nav>
</header>

<body>
    <h1>Edit User</h1>

    <a href="./admin.php">Retour sur la page adminstrateur</a>

    <form method="post" action="edit_user.php">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>"
            required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
            required><br><br>
        <label for="roles">Roles:</label>
        <input type="text" id="roles" name="roles" value="<?php echo htmlspecialchars($user['roles']); ?>"
            required><br><br>
        <input type="submit" value="Update User">
    </form>

    <form method="post" action="edit_user.php" style="margin-top: 20px;">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
        <input type="submit" name="delete" value="Delete User"
            onclick="return confirm('Are you sure you want to delete this user?');">
    </form>

</body>

</html>