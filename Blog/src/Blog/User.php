<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Person\Name;

class User
{
  private UUID $uuid;
  private Name $name;
  private string $username;

  /**
   * @param UUID $uuid
   * @param string $name
   * @param string $username
   */

  public function __construct(UUID $uuid, Name $name, string $username)
  {
    $this->uuid = $uuid;
    $this->name = $name;
    $this->username = $username;
  }

  /*public function __toString(): string  
  {
    return "Юзер $this->uuid с именем $this->name и логином $this->username." .  PHP_EOL;
    // return $this->username . PHP_EOL;
  }*/

  /**
   * Get the value of id
   */ 
  public function uuid() : UUID
  {
    return $this->uuid;
  }

  /**
   * Set the value of id
   *
   * @return  self
   */ 
  // public function setId($id)
  // {
  //   $this->id = $id;

  //   return $this;
  // }

  /**
   * @return Name
   */ 
  public function getName() : Name
  {
    return $this->name;
  }

  /**
   * Set the value of username
   *
   * @return  self
   */ 
  public function setName(Name $name) : void
  {
    $this->name = $name;    
  }

  /**
   * Get the value of username
   */ 
  public function getUsername() : string
  {
    return $this->username;
  }

  /**
   * Set the value of username
   *
   * @return  self
   */ 
  public function setUsername($username) : void
  {
    $this->username = $username;    
  }
}