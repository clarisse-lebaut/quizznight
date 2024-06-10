<?php
class BDD
{
  protected PDO $connexion;

  function __construct()
  {
    try {
      $this->connexion = new PDO("mysql:host=localhost;dbname=quizznight", "root", "");
      $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      throw new Exception($e->getMessage());
    }
  }

  function getConnexion(): PDO
  {
    return $this->connexion;
  }
}
