<?php
// Include files and instantiate it
require 'classConnectDB.php';
require '../config/config.php';

class User
{
    private $connexion;

    public function __construct($dbConnection)
    {
        $this->connexion = $dbConnection->getConnexion();
    }

    public function handleFormSubmission()
    {
        if ($_POST) {
            $username = $_POST["username"];
            $password = $_POST["password"];
            $email = $_POST["email"];
            $role = $_POST["role"];

            $hashed_password = hash('sha256', $password);

            try {
                $sql = "INSERT INTO user (username, password, email, roles) VALUES (:username, :password, :email, :roles)";
                $stmt = $this->connexion->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':roles', $role);
                $stmt->execute();

                header("Location: ../pages/login.php");
                exit();
            } catch (PDOException $e) {
                echo "Erreur: " . $e->getMessage();
            }
        }
    }
}

// Create a database connection instance
$dbConnection = new ConnectToDatabase();
$user = new User($dbConnection);
$user->handleFormSubmission();