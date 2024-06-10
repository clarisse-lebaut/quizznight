<?php
class Answer extends BDD
{
  private int $id;
  private string $label;

  function __construct()
  {
    parent::__construct();
  }

  function getId(): int
  {
    return $this->id;
  }

  function setId(int $id): void
  {
    $this->id = $id;
  }

  function getLabel(): string
  {
    return $this->label;
  }

  function setLabel(int $label): void
  {
    $this->label = $label;
  }
}
