<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;

use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\PostsRepositoryException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use \PDO;
use \PDOStatement;
use PDOException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;



class SqlitePostsRepository implements PostsRepositoryInterface
{
  public function __construct(private PDO $connection) {
  }

  public function save(Post $post): void
  {
  
  $statement = $this->connection->prepare(
    'INSERT INTO posts (uuid, author_uuid, title, text)
    VALUES (:uuid, :author_uuid, :title, :text)'
    );
  
  $statement->execute([
  ':uuid' => (string)$post->uuid(),
  ':author_uuid' => (string)$post->getPostAuthor()->uuid(),
  ':title' => $post->getPostTitle(),
  ':text' => $post->getPostText()
  ]);
  }

  

  public function get(UUID $uuid): Post
  {
    
   $statement = $this->connection->prepare('SELECT * FROM posts WHERE uuid = :uuid');
   $statement->execute([':uuid' => (string)$uuid]);

   return $this->getPost($statement, $uuid);
  //  $result = $statement->fetch(PDO::FETCH_ASSOC);

  //  if ($result === false) {
  //  throw new UserNotFoundException(
  //  "Cannot get user: $uuid"
  //  );
  //  }
  //   return new User(
  //   new UUID($result['uuid']),
  //   new Name($result['first_name'], $result['last_name']),
  //   $result['username']
  //  );
  }

  public function getByPostTitle(string $postTitle): Post
  {
    $statement = $this->connection->prepare(
    'SELECT * FROM posts WHERE title = :title'
   );
    $statement->execute([
    ':title' => $postTitle,
   ]);
    return $this->getPost($statement, $postTitle);
  }

  public function getPost(PDOStatement $statement, string $errorString) : Post
  {
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    
    $usersRepository = new SqliteUsersRepository($this->connection);


    if ($result === false) {
    throw new PostNotFoundException(
    "Cannot get post: $errorString"
    );
    }
    
    
    return new Post(
    new UUID($result['uuid']),
    //вернем uuid  из user
    $usersRepository->get(new UUID($result['author_uuid']))->uuid(),
    //или вернем uuid строкой
    // $result['author_uuid'],
    $result['title'],
    $result['text']
    );
  }

  public function deletePost(UUID $uuid): void
  {

    try {
      $statement = $this->connection->prepare(
      'DELETE FROM posts WHERE uuid = ?'
      );
      $statement->execute([(string)$uuid]);
      } catch (PDOException $e) {
      throw new PostsRepositoryException(
      $e->getMessage(), (int)$e->getCode(), $e
      );
    }
      
    
  //  $statement = $this->connection->prepare('DELETE FROM posts WHERE posts.uuid = :uuid');
  //  $statement->execute([':uuid' => (string)$uuid]);

  //  $result = $statement->fetch(PDO::FETCH_ASSOC);


  //  if ($result === false) {
  //  throw new PostNotFoundException(
  //  "Cannot find post: $uuid"
  //  );
  //  }
  }


}