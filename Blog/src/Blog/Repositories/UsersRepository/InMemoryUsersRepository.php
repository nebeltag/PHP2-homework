<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UsersRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;

class InMemoryUsersRepository implements UsersRepositoryInterface
{
  /**
   * @var User[]
   */
  private array $users = [];

  /**
   * @param User $user
   */
  public function save(User $user): void
  {
    $this->users[] = $user;
  }

  /**
   * @param UUID $uuid
   * @return User
   * @throws UserNotFoundException
   */
   public function get(UUID $uuid): User
   {
    foreach ($this->users as $user) {
     if  ((string)$user->uuid() === (string)$uuid) {
     return $user;
     }
    }

       throw new UserNotFoundException("User not found: $id");
   }

   public function getByUsername(string $username): User
   {
    
   }
}