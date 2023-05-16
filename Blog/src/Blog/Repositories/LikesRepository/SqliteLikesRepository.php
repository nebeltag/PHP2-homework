<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\LikesRepository;


use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Blog\Exceptions\LikeNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use \PDO;
use \PDOStatement;




class SqliteLikesRepository implements LikesRepositoryInterface
{
  public function __construct(private PDO $connection) {
  }

  public function save(Like $like): void
  {
  
  $statement = $this->connection->prepare(
    'INSERT INTO likes (uuid, post_uuid, author_uuid)
    VALUES (:uuid, :post_uuid, :author_uuid)'
    );
  
  $statement->execute([
  ':uuid' => (string)$like->uuid(),
  ':post_uuid' => (string)$like->getLikedPost(),
  ':author_uuid' => (string)$like->getLikeAuthor()
  ]);
  }

  

  public function get(UUID $uuid): Like
  {
    
   $statement = $this->connection->prepare('SELECT * FROM likes WHERE uuid = :uuid');
   $statement->execute([':uuid' => (string)$uuid]);

   

   return $this->getLike($statement, $uuid);
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

  
  public function getByPostUuid(string $postUuid): Like
  {
    $statement = $this->connection->prepare(
    'SELECT * FROM likes WHERE post_uuid = :post_uuid'
   );
    $statement->execute([
    ':post_uuid' => $postUuid,
   ]);

   
    return $this->getLike($statement, $postUuid);
    
  }
  
  public function getByPostAndAuthor(Like $like): bool
  {
    $statement = $this->connection->prepare(
    'SELECT * FROM likes WHERE post_uuid = :post_uuid AND author_uuid = :author_uuid'
   );
    $statement->execute([
      ':post_uuid' => (string)$like->getLikedPost(),
      ':author_uuid' => (string)$like->getLikeAuthor()
   ]);

    $result = $statement->fetch(PDO::FETCH_ASSOC);
   
    if($result){
      return false;
    }
  }

  public function getLike(PDOStatement $statement, string $errorString) : Like
  {
    $result = $statement->fetch(PDO::FETCH_ASSOC);
        
    
    if ($result === false) {
    throw new LikeNotFoundException(
    "Cannot get like: $errorString"
    );
    }
    
    
    return new Like(
    new UUID($result['uuid']),
    $result['post_uuid'],
    $result['author_uuid']
    );
  }
  

  public function deleteLike(UUID $uuid): void
  {
    
   $statement = $this->connection->prepare('DELETE FROM likes WHERE likes.uuid = :uuid');
   $statement->execute([':uuid' => (string)$uuid]);

  //  $result = $statement->fetch(PDO::FETCH_ASSOC);


  //  if ($result === false) {
  //  throw new PostNotFoundException(
  //  "Cannot find post: $uuid"
  //  );
  //  }
  }


}