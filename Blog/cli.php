<?php

require_once __DIR__ . '/vendor/autoload.php';

use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$usersRepository = new SqliteUsersRepository($connection);

// $usersRepository->save(new User(UUID::random(), new Name('Ivan', 'Nikitin'), 'admin'));
// $usersRepository->save(new User(UUID::random(), new Name('Anna', 'Petrova'), 'user'));

try {
  // $usersRepository->save(new User(UUID::random(), new Name('Ivan', 'Nikitin'), 'admin'));
   echo $usersRepository->get(new UUID('2818619e-d094-4f66-8cf5-bba83dc955bb'));
  // echo $usersRepository->getByUsername('admin2');
} catch(Exception $e) {
  echo $e->getMessage();
}

