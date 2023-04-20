<?php

namespace GeekBrains\LevelTwo\Person;

class Name 
{
  // private string $name;

  private string $firstName;
  private string $lastName;

  public function __construct (string $firstName, string $lastName)
  {
    $this->firstName = $firstName;
    $this->lastName = $lastName;
  }

  // public function __construct (string $name)
  // {
  //   $this->name = $name;
    
  // }

  public function __toString()
  {
    return $this->firstName . ' ' . $this->lastName;
    // return $this->name;
  }

  /**
   * Get the value of firstName
   */ 
  public function getFirstName() : string
  {
    return $this->firstName;
  }

  /**
   * Set the value of firstName
   *
   * @return  self
   */ 
  public function setFirstName(string $firstName) : void
  {
    $this->firstName = $firstName;
  }

  /**
   * Get the value of lastName
   */ 
  public function getLastName() : string
  {
    return $this->lastName;
  }

  /**
   * Set the value of lastName
   *
   * @return  self
   */ 
  public function setLastName(string $lastName) : void
  {
    $this->lastName = $lastName;    
  }
}