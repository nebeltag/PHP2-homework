<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UsersRepository;

use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use \PDO;
use \PDOStatement;

class SqliteUsersRepository implements UsersRepositoryInterface
{
  public function __construct(private PDO $connection) {
  }

  public function save(User $user): void
  {
  
  $statement = $this->connection->prepare(
    'INSERT INTO users (first_name, last_name, uuid, username, password)
    VALUES (:first_name, :last_name, :uuid, :username, :password)'
    );
  
  $statement->execute([
  ':first_name' => $user->getName()->getFirstName(),
  ':last_name' => $user->getName()->getLastName(),
  ':uuid' => (string)$user->uuid(),
  ':username' => $user->getUsername(),
  ':password' => $user->hashedPassword()
  ]);
  }

  /**
   * @throws userNotFoundException 
   * @throws invalidArgumentException 
   * */ 

  public function get(UUID $uuid): User
  {
    
   $statement = $this->connection->prepare('SELECT * FROM users WHERE uuid = :uuid');
   $statement->execute([':uuid' => (string)$uuid]);

   return $this->getUser($statement, $uuid);
  //  $result = $statement->fetch(PDO::FETCH_ASSOC);

  //  if ($result === false) {
  //  throw new UserNotFoundException(
  //  "Cannot get user: $uuid"
  //  );
  //  }
  //   return new User(
  //   new UUID($result['uuid']),
  //   new Name($result['first_name'], $result['last_name']),
  //   $result['username']
  //  );
  }

  public function getByUsername(string $username): User
  {
    $statement = $this->connection->prepare(
    'SELECT * FROM users WHERE username = :username'
   );
    $statement->execute([
    ':username' => $username,
   ]);
    return $this->getUser($statement, $username);
  }

  public function getUser(PDOStatement $statement, string $errorString) : User
  {
    
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result === false) {
    throw new UserNotFoundException(
    "Cannot get user: $errorString"
    );
    }
    return new User(
    new UUID($result['uuid']),
    new Name($result['first_name'], $result['last_name']),
    $result['username'],
    $result['password']
    );
  }


}