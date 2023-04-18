<?php

require_once __DIR__ . '/vendor/autoload.php';

use GeekBrains\LevelTwo\Blog\{User, Post};
use GeekBrains\LevelTwo\Person\{Name, Person};
use GeekBrains\LevelTwo\Blog\Repositories\InMemoryUsersRepository;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;


// spl_autoload_register(
// function ($className)
// {
//   $file = $className . ".php";
//   $file = str_replace(["\\", "GeekBrains/LevelTwo"], ["/", "src"], $file);
//   // var_dump($file);
//   if(file_exists($file))
//   {
//     include $file;
//   }
// });

// require_once 'User.php';
// require_once 'Name.php';
// require_once 'Person.php';

$faker = Faker\Factory::create('ru_Ru');
echo $faker->name() . PHP_EOL;
echo $faker->realText(rand(100,200)) . PHP_EOL;

$name = new Name('Peter', 'Sidorov');
$user = new User(1, $name, "Admin");
echo $user;


$person = new Person($name, new DateTimeImmutable());
// echo $person;

$post = new Post(
  1,
  new Person(
    new Name('Иван', 'Никитин'),
    new DateTimeImmutable(),
  ),
  'Всем привет!' . PHP_EOL
);

echo $post;

$name2 = new Name('Коля', 'Николаев');
$user2 = new User(2, $name2, 'User');
$userRepository = new InMemoryUsersRepository();

$userRepository->save($user);
$userRepository->save($user2);

try
{
   echo $userRepository->get(1);
   echo $userRepository->get(2);
   echo $userRepository->get(3);
}catch(UserNotFoundException | Exception $e) {
   echo $e->getMessage();
}