<?php
class User
{
    private $connexion;

    // Constructeur avec un paramètre de connexion PDO
    public function __construct(PDO $connexion)
    {
        $this->connexion = $connexion;
    }

    public function createUser($username, $email, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (username, email, password) VALUES (:username, :email, :password)";

        try {
            $stmt = $this->connexion->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->execute();

            // Récupérer l'ID de l'utilisateur après l'insertion dans la base de données
            $user_id = $this->connexion->lastInsertId();

            // Stocker l'ID de l'utilisateur dans la session
            session_start();
            $_SESSION['user_id'] = $user_id;

            return true;
        } catch (PDOException $e) {
            return "Erreur : " . $e->getMessage();
        }
    }
}
?>