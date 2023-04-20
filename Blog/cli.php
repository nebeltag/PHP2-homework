<?php

require_once __DIR__ . '/vendor/autoload.php';

use GeekBrains\LevelTwo\Blog\{User, Post, Comment};
use GeekBrains\LevelTwo\Person\{Name, Person};
use GeekBrains\LevelTwo\Blog\Repositories\InMemoryUsersRepository;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;


// spl_autoload_register(
// function ($className)
// {
//   $file = $className . ".php";
//   $file = str_replace(["\\", "GeekBrains/LevelTwo/"], ["/", "src/"], $file);
//   // var_dump($file);
//   if(file_exists($file))
//   {
//     include $file;
//   }
// });

$faker = Faker\Factory::create('ru_Ru');


// echo $faker->name() . PHP_EOL;
// echo $faker->realText(rand(100,200)) . PHP_EOL;

$name = new Name($faker->name());
$user = new User(1, $name, "Admin");

$name2 = new Name($faker->name());
$user2 = new User(2, $name2, 'User');

// echo $user;



/*$person = new Person($name, new DateTimeImmutable());
echo $person;

$post = new Post(
  1,
  new Person(
    new Name($user2),
    new DateTimeImmutable(),
  ),
  'Всем привет!' . PHP_EOL
);*/

// $post = new Post (
//   1, 
//   $user2, 
//   $faker->realText(rand(10,20)), 
//   $faker->realText(rand(100,200))
// );

// echo $post;

// $comment = new Comment (
//    1,
//    $user2,
//    $post,
//    $faker->realText(rand(20,50))
// );

// echo $comment;

$route = $argv[1] ?? null;

switch ($route) {
  case 'user':
    echo $user;
    break;
  case 'post':
    $post = new Post (
      1, 
      $user, 
      $faker->realText(rand(10,20)), 
      $faker->realText(rand(100,200))
    );
    echo $post;
    break;
  case 'comment':
    $post = new Post (
      1, 
      $user, 
      $faker->realText(rand(10,20)), 
      $faker->realText(rand(100,200))
      );
    $comment = new Comment (
      1,
      $user,
      $post,
      $faker->realText(rand(20,50))
     );
    echo $comment;
    break;
  default:
    echo "echo error try user post comment parameter";
    break;
}

// $userRepository = new InMemoryUsersRepository();

// $userRepository->save($user);
// $userRepository->save($user2);

// try
// {
//    echo $userRepository->get(1);
//    echo $userRepository->get(2);
//    echo $userRepository->get(3);
// }catch(UserNotFoundException | Exception $e) {
//    echo $e->getMessage();
// }