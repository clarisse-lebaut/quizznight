<?php
    class User {
        private $connexion;
        
        // Constructor Property Promotion
        public function __construct(
            public $db_host, 
            public $db_name, 
            public $db_user, 
            public $db_pass) {
            try {
                $this->connexion = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
                $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }
        
        public function createUser($username, $email, $password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user (username, email, password) VALUES (:username, :email, :password)";

            try {
                $stmt = $this->connexion->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                return "Erreur : " . $e->getMessage();
            }
        }

        public function __destruct() {
            $this->connexion = null;
        }
    }
?>
