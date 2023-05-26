<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Person\Name;

class User
{
  
  /**
   * @param UUID $uuid
   * @param Name $name
   * @param string $username
   * @param string $hashedPassword
   */

  public function __construct(
    private UUID $uuid, 
    private Name $name, 
    private string $username, 
    private string $hashedPassword)
  {
    
  }

  public function hashedPassword(): string
  {
     return $this->hashedPassword;
  }

  // Функция для вычисления хеша
  private static function hash(string $password, UUID $uuid): string
  {
     return hash('sha256', $uuid . $password);
  }

  // Функция для проверки предъявленного пароля
  public function checkPassword(string $password): bool
  {
      return $this->hashedPassword === self::hash($password, $this->uuid);
  }

  // Функция для создания нового пользователя
  public static function createFrom(
     string $username,
     string $password,
     Name $name
  ): self
  {   
      $uuid =  UUID::random();
      return new self( 
        $uuid,        
        $name,
        $username,
        self::hash($password, $uuid)
      );
    }



  public function __toString(): string  
  {
    return "Юзер $this->uuid с именем $this->name и логином $this->username." .  PHP_EOL;
    // return $this->username . PHP_EOL;
  }

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