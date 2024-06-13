<?php
class createQuizz
{
    private $connexion;
    // Méthode pour créer un nouveau quizz
    public function createNewQuizz($connexion)
    {
        if (isset($_POST['newQuizz'])) {
            $quizTitle = $_POST['quizTitle'];
            $description_quizz = $_POST['description_quizz']; // Récupérer la description du quizz
            $username = $_SESSION['username'];
            $creatorId = $_SESSION['user_id']; // Utilisez l'ID utilisateur de la session

            try {
                // Préparer la requête SQL
                $stmt = $connexion->prepare("INSERT INTO quiz (title, description, creator_id) VALUES (:title, :description, :creator_id)");
                // Exécuter la requête avec les paramètres
                $stmt->execute([
                    ':title' => $quizTitle,
                    ':description' => $description_quizz,
                    ':creator_id' => $creatorId
                ]);

                echo "Nouveau quiz créé avec succès!";

                // Redirection après la création
                header("Location: newQuiz.php");
                exit();
            } catch (PDOException $e) {
                echo "Erreur: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}
?>