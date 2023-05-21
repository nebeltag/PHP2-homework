<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\LikesRepository;


use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Blog\Exceptions\LikeNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\LikeAllReadyExists;
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

  public function checkUserLikeForPostExists($postUuid, $authorUuid): void
  {

    $statement = $this->connection->prepare(
      'SELECT * FROM likes WHERE post_uuid = :post_uuid AND author_uuid = :author_uuid'
      );
    
    $statement->execute([
    ':post_uuid' => $postUuid,
    ':author_uuid' => $authorUuid
    ]);

    $isExisted = $statement->fetch();

    if ($isExisted){
      throw new LikeAllReadyExists(
        'The users like for this post is allready exists'
      );
    }
  }

  public function get(UUID $uuid): Like
  {
    
   $statement = $this->connection->prepare('SELECT * FROM likes WHERE uuid = :uuid');
   $statement->execute([':uuid' => (string)$uuid]);

   return $this->getLike($statement, $uuid);
  }

  
  public function getByPostUuid(UUID $uuid): array
  {
    $statement = $this->connection->prepare(
    'SELECT * FROM likes WHERE post_uuid = :post_uuid'
   );
    $statement->execute([
    ':post_uuid' => (string)$uuid,
   ]);

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (!$result) {
      throw new LikeNotFoundException(
      "No likes to post with uuid = : " . $uuid
      );
    }

    
    $likes = [];
    foreach ($result as $like){
       $likes[] = new Like(
        new UUID($like['uuid']),
        new UUID($like['post_uuid']),
        new UUID($like['author_uuid'])
       );
    }

    return $likes;
    
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