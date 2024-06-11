<?php
    class Disconnect {
        private $connexion;
        public function disconnectModul(){
            // Vérifie si l'utilisateur a demandé à se déconnecter
            if (isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            header("Location: ../../index.php"); // Redirige vers la page d'accueil après déconnexion
            exit();
            }
        }
    }
?>