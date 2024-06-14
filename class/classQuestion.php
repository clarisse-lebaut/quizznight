<?php
class Question
{
    private $connexion;

    public function __construct($dbConnection)
    {
        $this->connexion = $dbConnection->getConnexion();
    }

    public function addQuestion($quiz_id, $question_text)
    {
        try {
            $sql = "INSERT INTO question (quiz_id, question_text) VALUES (:quiz_id, :question_text)";
            $stmt = $this->connexion->prepare($sql);
            $stmt->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
            $stmt->bindParam(':question_text', $question_text, PDO::PARAM_STR);
            $stmt->execute();

            return "Question ajoutée avec succès!";
        } catch (PDOException $e) {
            return "Erreur: " . $e->getMessage();
        }
    }

    public function getQuizzes()
    {
        try {
            $sql = "SELECT id, title FROM quiz";
            $stmt = $this->connexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Erreur: " . $e->getMessage();
        }
    }

    public function getQuestions()
    {
        try {
            $sql = "SELECT id, question_text FROM question";
            $stmt = $this->connexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Erreur: " . $e->getMessage();
        }
    }
}