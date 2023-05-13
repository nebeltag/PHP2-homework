<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository;

use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use \PDO;
use \PDOStatement;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;



class SqliteCommentsRepository implements CommentsRepositoryInterface
{
  public function __construct(private PDO $connection) {
  }

  public function save(Comment $comment): void
  {
  
  $statement = $this->connection->prepare(
    'INSERT INTO comments (uuid, post_uuid, author_uuid, text)
    VALUES (:uuid, :post_uuid, :author_uuid, :text)'
    );
  
  $statement->execute([
  ':uuid' => (string)$comment->uuid(),
  ':post_uuid' => $comment->getCommentedPost()->uuid(),
  ':author_uuid' => (string)$comment->getCommentAuthor()->uuid(),
  ':text' => $comment->getCommentText()
  ]);
  }

  

  public function get(UUID $uuid): Comment
  {
    
   $statement = $this->connection->prepare('SELECT * FROM comments WHERE uuid = :uuid');
   $statement->execute([':uuid' => (string)$uuid]);

   return $this->getComment($statement, $uuid);
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

  // public function getByPostTitle(string $postTitle): Post
  // {
  //   $statement = $this->connection->prepare(
  //   'SELECT * FROM posts WHERE title = :title'
  //  );
  //   $statement->execute([
  //   ':title' => $postTitle,
  //  ]);
  //   return $this->getComment($statement, $postTitle);
  // }

  public function getComment(PDOStatement $statement, string $errorString) : Comment
  {
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $usersRepository = new SqliteUsersRepository($this->connection);
    $postsRepository = new SqlitePostsRepository($this->connection);

    if ($result === false) {
    throw new CommentNotFoundException(
    "Cannot get comment: $errorString"
    );
    }
    return new Comment(
    new UUID($result['uuid']),

    //вернем uuid  из user
    $usersRepository->get(new UUID($result['author_uuid']))->uuid(),
    //или вернем uuid строкой
    // $result['author_uuid'],

    //вернем uuid  из post
    $postsRepository->get(new UUID($result['post_uuid']))->uuid(),
    //или вернем uuid строкой
    // $result['post_uuid'],    
    
    $result['text']
    );
  }

  public function deleteComment(UUID $uuid): void
  {
    
   $statement = $this->connection->prepare('DELETE FROM comments WHERE uuid = :uuid');
   $statement->execute([':uuid' => (string)$uuid]);

   $result = $statement->fetch(PDO::FETCH_ASSOC);


   if ($result === false) {
   throw new CommentNotFoundException(
   "Cannot find comment: $uuid"
   );
   }
  }
}