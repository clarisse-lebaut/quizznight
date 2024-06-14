<?php
class Answer
{
    private $connexion;

    public function __construct($dbConnection)
    {
        $this->connexion = $dbConnection->getConnexion();
    }

    public function addAnswer($question_id, $answer_text)
    {
        try {
            $sql = "INSERT INTO answer (question_id, answer_text) VALUES (:question_id, :answer_text)";
            $stmt = $this->connexion->prepare($sql);
            $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
            $stmt->bindParam(':answer_text', $answer_text, PDO::PARAM_STR);
            $stmt->execute();

            return "Réponse ajoutée avec succès!";
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

    public function getAnswers()
    {
        try {
            $sql = "SELECT id, answer_text FROM answer";
            $stmt = $this->connexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Erreur: " . $e->getMessage();
        }
    }
}