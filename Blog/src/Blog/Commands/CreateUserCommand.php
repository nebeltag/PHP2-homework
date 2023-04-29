<?php

namespace GeekBrains\LevelTwo\Blog\Commands;

use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;

//php cli.php username=ivan first_name=Ivan last_name=Nikitin

class CreateUserCommand
{

  public function __construct(private UsersRepositoryInterface $usersRepository)
  {

  }

    public function handle(Arguments $arguments): void
    {
    // $input = $this->parseRawInput($rawInput);
    // $username = $input['username'];

    $username = $arguments->get('username');

    // Проверяем, существует ли пользователь в репозитории
    if ($this->userExists($username)) {
    // Бросаем исключение, если пользователь уже существует
    throw new CommandException("User already exists: $username");
    }
      // Сохраняем пользователя в репозиторий
    $this->usersRepository->save(new User(
      UUID::random(),
      new Name(
      $arguments->get('first_name'), 
      $arguments->get('last_name')),
      $username,
     ));
    }
    
    private function userExists(string $username): bool
    {
      try {
      // Пытаемся получить пользователя из репозитория
      $this->usersRepository->getByUsername($username);
      } catch (UserNotFoundException) {
      return false;
      }
      return true;
    }
         

}