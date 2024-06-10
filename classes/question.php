<?php
class Question extends BDD
{
  private int $id;
  private int $answer_id;
  private string $label;

  function __construct()
  {
    parent::__construct();
  }

  function create(int $answer_id, string $label): void
  {
    try {
      $query = "INSERT INTO question (answer_id, label) VALUES (:answer_id, :label);";
      $stmt = $this->connexion->prepare($query);
      $stmt->execute(array("answer_id" => $answer_id, "label" => $label));
    } catch (PDOException $e) {
      throw new Exception($e->getMessage());
    }
  }

  function getId(): int
  {
    return $this->id;
  }

  function getAnswerId(): int
  {
    return $this->answer_id;
  }

  function getLabel(): string
  {
    return $this->label;
  }
}
