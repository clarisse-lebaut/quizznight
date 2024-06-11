<?php
    class ConnectToDatabase {
        private $connexion;

        public function __construct() {
            try {
                $this->connexion = new PDO('mysql:host=localhost;dbname=test;charset=utf8', "root", "");
                // Configure l'exception PDO pour gérer les erreurs
                $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }

        public function getConnexion() {
            return $this->connexion;
        }
    }
?>
