<?php
    class Question {
        private $connexion;
        
        // Constructeur avec un paramètre de connexion PDO
        public function __construct(PDO $connexion) {
            $this->connexion = $connexion;
        }
        
        public function createQuestion($questionText) {
            $sql = "INSERT INTO question (question_text) VALUES (:question_text)";
            try {
                $stmt = $this->connexion->prepare($sql);
                $stmt->bindParam(':question_text', $questionText);
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                return "Erreur : " . $e->getMessage();
            }
        }
    }
?>
