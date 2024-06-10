<?php
class Answer extends BDD
{
  private int $id;
  private string $label;

  function __construct()
  {
    parent::__construct();
  }

  function create(string $label): void
  {
    try {
      $query = "INSERT INTO answer (label) VALUES (:label);";
      $stmt = $this->connexion->prepare($query);
      $stmt->execute(array("label" => $label));
    } catch (PDOException $e) {
      throw new Exception($e->getMessage());
    }
  }

  function getId(): string
  {
    return $this->id;
  }

  function getLabel(): string
  {
    return $this->label;
  }
}
