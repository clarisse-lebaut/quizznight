<?php
class ConnectToDatabase
{
    private $connexion;

    public function __construct()
    {
        try {
            $this->connexion = new PDO('mysql:host=localhost;dbname=quiznight;charset=utf8', "root", "");
            // Configure the PDO exception to handle errors
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function getConnexion()
    {
        return $this->connexion;
    }
}
