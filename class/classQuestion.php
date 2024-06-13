<?php
class Question {
    public function createQuestions($connexion, $question_text, $quiz_id) {
        try {
            // Préparer la requête SQL
            $stmt = $connexion->prepare("INSERT INTO question (question_text, quiz_id) VALUES (:question_text, :quiz_id)");
            // Exécuter la requête avec les paramètres
            $stmt->execute([
                ':question_text' => $question_text,
                ':quiz_id' => $quiz_id,
            ]);

            echo "Nouvelle question créée avec succès!";
        } catch (PDOException $e) {
            echo "Erreur: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
