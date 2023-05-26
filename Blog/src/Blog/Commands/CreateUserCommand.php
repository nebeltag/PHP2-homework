<?php

namespace GeekBrains\LevelTwo\Blog\Commands;

use GeekBrains\LevelTwo\Blog\Exceptions\ArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use Psr\Log\LoggerInterface;


//php cli.php username=ivan first_name=Ivan last_name=Nikitin password=123

class CreateUserCommand
{

  public function __construct(
    private UsersRepositoryInterface $usersRepository,
    private LoggerInterface $logger
    )
  {

  }

    public function handle(Arguments $arguments): void
    {
    // $input = $this->parseRawInput($rawInput);
    // $username = $input['username'];

    // Логируем информацию о том, что команда запущена
    // Уровень логирования – INFO
    $this->logger->info("Create user command started");

    $username = $arguments->get('username');

       // Вычисляем SHA-256-хеш пароля
    // $hash = hash('sha256', $password);

    // Проверяем, существует ли пользователь в репозитории
    if ($this->userExists($username)) {

    // Логируем сообщение с уровнем WARNING
    $this->logger->warning("User already exists: $username");


    // Бросаем исключение, если пользователь уже существует
    throw new CommandException("User already exists: $username");
    }

    // Создаём объект пользователя
    // Функция createFrom сама создаст UUID
    // и захеширует пароль
    $user = User::createFrom(
       $username,
       $arguments->get('password'),
       new Name(
          $arguments->get('first_name'),
          $arguments->get('last_name')
         )
     );
  

      // Сохраняем пользователя в репозиторий
    $this->usersRepository->save($user);

     // Логируем информацию о новом пользователе
     $this->logger->info("User created:" . $user->uuid());

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