<?php

namespace App\Core;

class Card
{

  private $name;
  private $color;

  public function __construct(string $name, string $color)
  {
    $this->name = $name;
    $this->color = $color;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): void
  {
    $this->name = $name;
  }

  public function getColor() : string
  {
    return $this->color;
  }

  public function __toString() : string
  {
      return $this->name . " de " . $this->color;
  }

}