<?php
require '../config/config.php';

class Quiz
{
    private $connexion;

    public function __construct($dbConnection)
    {
        $this->connexion = $dbConnection->getConnexion();
    }

    public function addQuiz($title, $description, $creator_id)
    {
        if ($creator_id !== null) {
            try {
                $sql = "INSERT INTO quiz (title, description, creator_id) VALUES (:title, :description, :creator_id)";
                $stmt = $this->connexion->prepare($sql);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':creator_id', $creator_id);
                $stmt->execute();

                header('Location: ../pages/welcome.php');
                exit();
            } catch (PDOException $e) {
                echo "Erreur: " . $e->getMessage();
            }
        } else {
            echo "Error: Creator ID is not set.";
        }
    }
}