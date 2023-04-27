<?php

require_once __DIR__ . '/vendor/autoload.php';

use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Commands\CreateUserCommand;
use GeekBrains\LevelTwo\Blog\Commands\Arguments;

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$usersRepository = new SqliteUsersRepository($connection);
$postsRepository = new SqlitePostsRepository($connection);

//Сохраняем новых юзеров в базу
// $usersRepository->save(new User(UUID::random(), new Name('Ivan', 'Nikitin'), 'admin'));
// $usersRepository->save(new User(UUID::random(), new Name('Anna', 'Petrova'), 'user'));

//Сохраняем новыe посты в базу
// $postsRepository->save(new Post(UUID::random(), 
// $usersRepository->getByUsername('ivan'), '123', '456'));

// $command = new CreateUserCommand($usersRepository); 

try {
  // $usersRepository->save(new User(UUID::random(), new Name('Ivan', 'Nikitin'), 'admin'));
  // echo $usersRepository->get(new UUID('2818619e-d094-4f66-8cf5-bba83dc955bb'));
  // echo $usersRepository->getByUsername('admin');

  // $postsRepository->save(new Post(UUID::random(), 
  // $usersRepository->getByUsername('ivan2'), 'ааа', 'ввв'));  
  // echo $postsRepository->get(new UUID('a29b6ea1-5732-4bd1-86d0-455a79351a13'));
  // echo $postsRepository->getByPostTitle('123');

} catch(Exception $e) {
  echo $e->getMessage();
}

// try {
  
//   $command->handle(Arguments::fromArgv($argv));
// } catch(Exception $e) {
//   echo $e->getMessage();
// }

