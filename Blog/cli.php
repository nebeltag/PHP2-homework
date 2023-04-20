<?php

use GeekBrains\LevelTwo\Blog\{User, Post, Comment};
use GeekBrains\LevelTwo\Person\{Name, Person};
use GeekBrains\LevelTwo\Blog\Repositories\InMemoryUsersRepository;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;

include __DIR__ . '/vendor/autoload.php';


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

$name = new Name(
  $faker->firstName('female'),
  $faker->lastName('female')
);

$user = new User(
  $faker->randomDigitNotNull(), 
  $name, 
  $faker->sentence(1));


$route = $argv[1] ?? null;

switch ($route) {
  case 'user':
    echo $user;
    break;
  case 'post':
    $post = new Post (
      $faker->randomDigitNotNull(), 
      $user, 
      $faker->realText(rand(10,20)), 
      $faker->realText(rand(100,200))
    );
    echo $post;
    break;
  case 'comment':
    $post = new Post (
      $faker->randomDigitNotNull(), 
      $user, 
      $faker->realText(rand(10,20)), 
      $faker->realText(rand(100,200))
      );
    $comment = new Comment (
      $faker->randomDigitNotNull(),
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