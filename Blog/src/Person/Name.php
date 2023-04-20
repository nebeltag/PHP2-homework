<?php

namespace GeekBrains\LevelTwo\Person;

class Name 
{
  private string $name;

  /*private string $firstName;
  private string $lastName;

  public function __construct (string $firstName, string $lastName)
  {
    $this->firstName = $firstName;
    $this->lastName = $lastName;
  }*/

  public function __construct (string $name)
  {
    $this->name = $name;
    
  }

  public function __toString()
  {
    // return $this->firstName . ' ' . $this->lastName;
    return $this->name;
  }
}