<?php

namespace GeekBrains\LevelTwo\tests\Actions;

use GeekBrains\LevelTwo\Http\Actions\Users\FindByUsername;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use PHPUnit\Framework\TestCase;

class FindByUsernameActionTest extends TestCase
{
  // Запускаем тест в отдельном процессе

  /**
    * @runInSeparateProcess
    * @preserveGlobalState disabled
    */

   // Тест, проверяющий, что будет возвращён неудачный ответ,
   // если в запросе нет параметра username
   public function testItReturnsErrorResponseIfNoUsernameProvided(): void
   {
     // Создаём объект запроса
     // Вместо суперглобальных переменных
     // передаём простые массивы
     $request = new Request([], []);

     // Создаём стаб репозитория пользователей
     $usersRepository = $this->usersRepository([]);

     //Создаём объект действия
     $action = new FindByUsername($usersRepository);

     // Запускаем действие
     $response = $action->handle($request);

     // Проверяем, что ответ - неудачный
     $this->assertInstanceOf(ErrorResponse::class, $response);

     // Описываем ожидание того, что будет отправлено в поток вывода
     $this->expectOutputString('{"success":false,"reason":"No such query param
      in the request: username"}');

      // Отправляем ответ в поток вывода
      $response->send();
   }

   // Функция, создающая стаб репозитория пользователей,
   // принимает массив "существующих" пользователей
   private function usersRepository(array $users): UsersRepositoryInterface
   {
     // В конструктор анонимного класса передаём массив пользователей
     return new class($users) implements UsersRepositoryInterface {
        public function __construct(
        private array $users
        ) {
        }

        public function save(User $user): void
        {
        }

        public function get(UUID $uuid): User
        {
          throw new UserNotFoundException("Not found");
        }

        public function getByUsername(string $username): User
        {
          foreach ($this->users as $user) {
             if ($user instanceof User && $username === $user->username())
           {
             return $user;
            }
          }
          throw new UserNotFoundException("Not found");
        }
     };
   }
}



