<?php

use GeekBrains\LevelTwo\Blog\Container\DIContainer;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepository\SqliteLikesRepository;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepository\LikesRepositoryInterface;
use GeekBrains\LevelTwo\Http\Auth\IdentificationInterface;
use GeekBrains\LevelTwo\Http\Auth\JsonBodyUuidIdentification;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Dotenv\Dotenv;


// Подключаем автозагрузчик Composer
require_once __DIR__ . '/vendor/autoload.php';

// Загружаем переменные окружения из файла .env
Dotenv::createImmutable(__DIR__)->safeLoad();

// Создаём объект контейнера ..
$container = new DIContainer();

// .. и настраиваем его:

// 1. подключение к БД
$container->bind(
PDO::class,
new PDO('sqlite:' . __DIR__ . '/' . $_ENV['SQLITE_DB_PATH'])
);


// Выносим объект логгера в переменную

$logger = (new Logger('blog'));
// Включаем логирование в файлы,
// если переменная окружения LOG_TO_FILES
// содержит значение 'yes'
   if ('yes' === $_SERVER['LOG_TO_FILES']) {
   $logger
   ->pushHandler(new StreamHandler(
    __DIR__ . '/logs/blog.log'
    ))
    ->pushHandler(new StreamHandler(
    __DIR__ . '/logs/blog.error.log',
    level: Logger::ERROR,
    bubble: false,
    ));
    }
// Включаем логирование в консоль,
// если переменная окружения LOG_TO_CONSOLE
// содержит значение 'yes'
    if ('yes' === $_SERVER['LOG_TO_CONSOLE']) {
    $logger
    ->pushHandler(
    new StreamHandler("php://stdout")
    );
    }
    
//7.Добавляем аутентификатор в контейнер
$container->bind(
  IdentificationInterface::class,
  JsonBodyUuidIdentification::class
  );

// 6.Добавляем логгер в контейнер
$container->bind(
  LoggerInterface::class,
  $logger
  );
  

// 2. репозиторий статей
$container->bind(
  PostsRepositoryInterface::class,
  SqlitePostsRepository::class
);

// 3. репозиторий пользователей
$container->bind(
  UsersRepositoryInterface::class,
  SqliteUsersRepository::class
);

// 4. репозиторий комментариев
$container->bind(
  CommentsRepositoryInterface::class,
  SqliteCommentsRepository::class
);

// 5. репозиторий лайков
$container->bind(
  LikesRepositoryInterface::class,
  SqliteLikesRepository::class
);


// Возвращаем объект контейнера
return $container;