<?php

use GeekBrains\LevelTwo\Http\{Request, SuccessfulResponse, ErrorResponse};
use GeekBrains\LevelTwo\Blog\Exceptions\{AppException, HttpException};
use GeekBrains\LevelTwo\Http\Actions\Posts\{CreatePost, FindByUuid, DeletePost};
use GeekBrains\LevelTwo\Http\Actions\Users\{FindByUsername, CreateUser};
use GeekBrains\LevelTwo\Http\Actions\Comments\{CreateComment, DeleteComment};
use GeekBrains\LevelTwo\Http\Actions\Likes\{CreateLike, DeleteLike, FindByPostUuid};
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepository\SqliteLikesRepository;
use Psr\Log\LoggerInterface;


// Подключаем файл bootstrap.php
// и получаем настроенный контейнер
$container = require __DIR__ . '/bootstrap.php';

// Получаем объект логгера из контейнера
$logger = $container->get(LoggerInterface::class);

$request = new Request(
  $_GET,
  $_SERVER,
  file_get_contents('php://input'),
);

try {
   $path = $request->path();
} catch (HttpException) {
   // Логируем сообщение с уровнем WARNING
   $logger->warning($e->getMessage());

   (new ErrorResponse)->send();
   return;
}

try {
   $method = $request->method();
} catch (HttpException) {
   // Логируем сообщение с уровнем WARNING
  $logger->warning($e->getMessage());

  (new ErrorResponse)->send();
  return;
}

// Ассоциируем маршруты с именами классов действий,
// вместо готовых объектов
$routes = [
    'GET' => [
       '/users/show' => FindByUsername::class,
       '/posts/show' => FindByUuid::class,
       '/likes/show' => FindByPostUuid::class,
    ],
    'POST' => [
        '/posts/create' => CreatePost::class,
        '/users/create' => CreateUser::class,
        '/posts/comment' => CreateComment::class,
        '/likes/create' => CreateLike::class
    ],
    'DELETE' => [
      '/comments' => DeleteComment::class,
      '/posts' => DeletePost::class,
      '/likes' => DeleteLike::class
    ],
];

if (!array_key_exists($method, $routes) || !array_key_exists($path, $routes[$method])) {
// Логируем сообщение с уровнем NOTICE
$message = "Route not found: $method $path";
$logger->notice($message);
(new ErrorResponse($message))->send();
return;
}


// Получаем имя класса действия для маршрута
$actionClassName = $routes[$method][$path];

// С помощью контейнера
// создаём объект нужного действия
$action = $container->get($actionClassName);
try {
   $response = $action->handle($request);
   } catch (AppException $e) {
   // Логируем сообщение с уровнем ERROR
   $logger->error($e->getMessage(), ['exception' => $e]);

   (new ErrorResponse($e->getMessage()))->send();
    die();
   }
   $response->send();



//http.php без контейнера
/*
require_once __DIR__ . '/vendor/autoload.php';
$request = new Request($_GET, $_SERVER, file_get_contents('php://input'));

// $path = $request->path();
// $name = $request->query("name");
// $header = $request->header('cookie');*/


// echo "$path\n";
// echo "$name\n";
// echo "$header";

// Создаём объект ответа
// $response = new SuccessfulResponse([
//   'name' => 'Maxim',
//   'age' => 15
//   ]);
  // Отправляем ответ
  // $response->send();

  /*$routes = [
    // Создаём действие, соответствующее пути /users/show
    '/users/show' => new FindByUsername(
    // Действию нужен репозиторий
    new SqliteUsersRepository(
    // Репозиторию нужно подключение к БД
    new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
    )
    ),
    // Второй маршрут
    // '/posts/show' => new FindByUuid(
    // new SqlitePostsRepository(
    // new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
    // )
    // ),
    ];*/

/*
 try {
    // Пытаемся получить путь из запроса
    $path = $request->path();
    } catch (HttpException) {
    // Отправляем неудачный ответ,
    // если по какой-то причине
    // не можем получить путь
    (new ErrorResponse)->send();
    // Выходим из программы
    return;
    }

   try {
      // Пытаемся получить HTTP-метод запроса
      $method = $request->method();
      } catch (HttpException) {
      // Возвращаем неудачный ответ,
      // если по какой-то причине
      // не можем получить метод
          (new ErrorResponse)->send();
      return;
   }

   $routes = [
      // Добавили ещё один уровень вложенности
      // для отделения маршрутов,
      // применяемых к запросам с разными методами
         'GET' => [
         '/users/show' => new FindByUsername(
             new SqliteUsersRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
             )
           ),
         '/posts/show' => new FindByUuid(
             new SqlitePostsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
             )
           ),
           
         ],
         'POST' => [
          // Добавили новый маршрут
           '/posts/create' => new CreatePost(
             new SqlitePostsRepository(
                new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
             ),
             new SqliteUsersRepository(
                 new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
              )
             ),
            '/users/create' => new CreateUser(
               new SqliteUsersRepository(
                  new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
               ),
            ),
            '/posts/comment' => new CreateComment(
               new SqliteCommentsRepository(
                  new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
               ),
               new SqliteUsersRepository(
                  new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
               ),
               new SqlitePostsRepository(
                  new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
               ),
            ),
         ],


         'DELETE' => [
            // Добавили новый маршрут
             '/comments' => new DeleteComment(
               new SqliteCommentsRepository(
                  new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
               ),
            ),
            // Добавили новый маршрут
            '/posts' => new DeletePost(
               new SqlitePostsRepository(
                  new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
               ),
            ),
         ]   

         
         
   ];



  // Если у нас нет маршрута для пути из запроса -
  // отправляем неуспешный ответ
  if (!array_key_exists($path, $routes[$method])) {
     (new ErrorResponse('Not found'))->send();
   return;
  }

  // Выбираем найденное действие
  $action = $routes[$method][$path];
  try {
    // Пытаемся выполнить действие,
    // при этом результатом может быть
    // как успешный, так и неуспешный ответ
     $response = $action->handle($request);

     // Отправляем ответ
     $response->send();
  } catch (Exception $e) {
  // Отправляем неудачный ответ,
  // если что-то пошло не так
     (new ErrorResponse($e->getMessage()))->send();
  }

  // Отправляем ответ
//   $response->send();*/
  
    


