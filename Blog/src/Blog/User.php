<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Person\Name;

class User
{
  private int $id;
  private Name $username;
  private string $login;

  /**
   * @param int $id
   * @param Name $username
   * @param string $login
   */

  public function __construct(int $id, Name $username, string $login)
  {
    $this->id = $id;
    $this->username = $username;
    $this->login = $login;
  }

  
  /**
   * Get the value of id
   */ 
  public function id() : int
  {
    return $this->id;
  }

  /**
   * Set the value of id
   *
   * @return  self
   */ 
  public function setId($id) : void
  {
    $this->id = $id;

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
  public function setUsername(Name $username) : void
  {
    $this->username = $username;

    
  }

  /**
   * Get the value of login
   */ 
  public function getLogin() : string
  {
    return $this->login;
  }

  /**
   * Set the value of login
   *
   * @return  self
   */ 
  public function setLogin(string $login) : void
  {
    $this->login = $login;

  }

  public function __toString(): string  
  {
    return "Юзер $this->id с именем $this->username и логином $this->login." .  PHP_EOL;
    // return $this->username . PHP_EOL;
  }

}