<?php

namespace GeekBrains\LevelTwo\Commands;

use GeekBrains\LevelTwo\Blog\Commands\{Arguments, CreateUserCommand};
use GeekBrains\LevelTwo\Blog\Exceptions\{CommandException,UserNotFoundException,
ArgumentsException};
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\{DummyUsersRepository,
UsersRepositoryInterface};
use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\{User, UUID};
use GeekBrains\Blog\UnitTests\DummyLogger;

use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{  
   // Способ 1. Проверяем наличие юзера с использованием stab(заглушка, чучело)

   // Проверяем, что команда создания пользователя бросает исключение,
   // если пользователь с таким именем уже существует
   public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
   {

      // Создаём объект команды
      // У команды одна зависимость - UsersRepositoryInterface
      // Передаём наш стаб в качестве реализации UsersRepositoryInterface
      $command = new CreateUserCommand(new DummyUsersRepository(), new DummyLogger());

      // Описываем тип ожидаемого исключения
      $this->expectException(CommandException::class);
      // и его сообщение
      $this->expectExceptionMessage('User already exists: Ivan');
      
      // Запускаем команду с аргументами
      $command->handle(new Arguments(['username' => 'Ivan']));
   }

   //----------------------------------------------------------------------

   //Способ 2. Проверка first_name с использованием анонимного класса

   // Тест проверяет, что команда действительно требует имя пользователя

   public function testItRequiresFirstName(): void
   {
   // $usersRepository - это объект анонимного класса,
   // реализующего контракт UsersRepositoryInterface
        $usersRepository = new class implements UsersRepositoryInterface {
          public function save(User $user): void
          {
             // Ничего не делаем
          }

          public function get(UUID $uuid): User
          {
             // И здесь ничего не делаем
            throw new UserNotFoundException("Not found");
          }

          public function getByUsername(string $username): User
          {
              // И здесь ничего не делаем
            throw new UserNotFoundException("Not found");
          }
        };

   // Передаём объект анонимного класса в качестве реализации UsersRepositoryInterface
        $command = new CreateUserCommand($usersRepository, new DummyLogger());
         
   // Ожидаем, что будет брошено исключение
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('No such argument: first_name');

   // Запускаем команду
        $command->handle(new Arguments(['username' => 'Ivan']));
   }

   //--------------------------------------------------------------

   //Способ 3. Проверка last_name с использованием анонимного класса,
   // при этом создание объекта анонимного класса будет вынесено в отдельную 
   // функцию для дальнейшего ее переиспользования и избежания дублирования кода  
   
   // Функция возвращает объект типа UsersRepositoryInterface
   private function makeUsersRepository(): UsersRepositoryInterface
   {
        return new class implements UsersRepositoryInterface {
          public function save(User $user): void
          {
          }

          public function get(UUID $uuid): User
          {
             throw new UserNotFoundException("Not found");
          }

          public function getByUsername(string $username): User

          {
            throw new UserNotFoundException("Not found");
          }
       };
   }
   

   // Тест проверяет, что команда действительно требует фамилию пользователя

   public function testItRequiresLastName(): void
   {
     // Передаём в конструктор команды объект, возвращаемый нашей функцией
     $command = new CreateUserCommand($this->makeUsersRepository(), new DummyLogger());

     $this->expectException(ArgumentsException::class);
     $this->expectExceptionMessage('No such argument: last_name');

     $command->handle(new Arguments([
       'username' => 'Ivan',
       // Нам нужно передать имя пользователя,
       // чтобы дойти до проверки наличия фамилии
       'first_name' => 'Ivan',
     ]));
   }

   // Создание мока(mock)
   // Тест, проверяющий, что команда сохраняет пользователя в репозитории

   public function testItSavesUserToRepository(): void
   {
      // Создаём объект анонимного класса
      $usersRepository = new class implements UsersRepositoryInterface {
          // В этом свойстве мы храним информацию о том,
          // был ли вызван метод save
          private bool $called = false;

          public function save(User $user): void
          {
            // Запоминаем, что метод save был вызван
            $this->called = true;
          }

          public function get(UUID $uuid): User
          {
             throw new UserNotFoundException("Not found");
          }

          public function getByUsername(string $username): User
          {
             throw new UserNotFoundException("Not found");
          }

          // Этого метода нет в контракте UsersRepositoryInterface,
          // но ничто не мешает его добавить.
          // С помощью этого метода мы можем узнать,
          // был ли вызван метод save
          public function wasCalled(): bool
          {
             return $this->called;
          }
      };

      // Передаём наш мок в команду
      $command = new CreateUserCommand($usersRepository, new DummyLogger());

      // Запускаем команду
      $command->handle(new Arguments([
        'username' => 'Ivan',
        'first_name' => 'Ivan',
        'last_name' => 'Nikitin',
      ]));

      // Проверяем утверждение относительно мока,
      // а не утверждение относительно команды
      $this->assertTrue($usersRepository->wasCalled());
   }


}
