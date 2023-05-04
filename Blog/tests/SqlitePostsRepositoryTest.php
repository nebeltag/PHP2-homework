<?php

namespace GeekBrains\LevelTwo;

use PDO;
use PDOStatement;

use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\{User, UUID, Post};
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;

use PHPUnit\Framework\TestCase;

class SqlitePostsRepositoryTest extends TestCase 
{
     public function testItSavesPostToDataBase() : void 
     {
          $connectionStub = $this->createStub(PDO::class);
          $statementMock = $this->createMock(PDOStatement::class);

          //TODO Настроить стабы и моки

          $statementMock
          ->expects($this->once()) 
          ->method('execute') 
          ->with([ 
            ':title' => 'title',
            ':text' => 'text',
            ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
            ':author_uuid' => '123e4567-e89b-12d3-a456-426614174000'
      ]);

          $connectionStub->method('prepare')->willReturn($statementMock);

          $repository = new SqlitePostsRepository($connectionStub);

          $user = new User( 
               new UUID('123e4567-e89b-12d3-a456-426614174000'),
               new Name('Ivan', 'Nikitin'),
               'ivan123'
          );

          $repository->save(
               new Post(
                new UUID('123e4567-e89b-12d3-a456-426614174000'),
                $user,
                'title',
                'text'    
               )
          );
     }

     public function testItThrowsAnExceptionWhenPostNotFound() : void 
     {
          $connectionMock = $this->createStub(PDO::class);
          $statementStub = $this->createStub(PDOStatement::class);

          $statementStub->method('fetch')->willReturn(false);
          $connectionMock->method('prepare')->willReturn($statementStub);
  
          $repository = new SqlitePostsRepository($connectionMock);
          $this->expectException(PostNotFoundException::class);
          $this->expectExceptionMessage('Cannot get post: a29b6ea1-5732-4bd1-86d0-455a79351a23');
  
          $repository->get(new UUID('a29b6ea1-5732-4bd1-86d0-455a79351a23'));
     }

     public function testItGetPostByUuid() : void 
     {
          $connectionStub = $this->createStub(PDO::class);
          $statementMock = $this->createMock(PDOStatement::class);

          $statementMock->method('fetch')->willReturn(
               [                     
                    'uuid' => 'a29b6ea1-5732-4bd1-86d0-455a79351a23',
                    'first_name' => 'Ivan',
                    'last_name' => 'Nikitin',
                    'username' => 'ivan123',
                    'author_uuid' => 'a29b6ea1-5732-4bd1-86d0-455a79351a23',
                    'title' => 'title',
                    'text' => 'text'                  
                    
               ]
          );
          $connectionStub->method('prepare')->willReturn($statementMock);

          $postRepository = new SqlitePostsRepository($connectionStub);
          $post = $postRepository->get(new UUID('a29b6ea1-5732-4bd1-86d0-455a79351a23'));
           
          $this->assertSame('a29b6ea1-5732-4bd1-86d0-455a79351a23', (string)$post->uuid());
     }


}