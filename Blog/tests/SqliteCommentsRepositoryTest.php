<?php

namespace GeekBrains\Blog\UnitTests;

use PDO;
use PDOStatement;

use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\{User, UUID, Post, Comment};
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;

use PHPUnit\Framework\TestCase;

class SqliteCommentsRepositoryTest extends TestCase 
{
     public function testItSavesCommentToDataBase() : void 
     {
          $connectionStub = $this->createStub(PDO::class);
          $statementMock = $this->createMock(PDOStatement::class);

          //TODO Настроить стабы и моки

          $statementMock
          ->expects($this->once()) 
          ->method('execute') 
          ->with([ 
            ':post_uuid' => '123e4567-e89b-12d3-a456-426614174000',
            ':text' => 'commentText',
            ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
            ':author_uuid' => '123e4567-e89b-12d3-a456-426614174000'
      ]);

          $connectionStub->method('prepare')->willReturn($statementMock);

          $repository = new SqliteCommentsRepository($connectionStub);

          $user = new User( 
               new UUID('123e4567-e89b-12d3-a456-426614174000'),
               new Name('Ivan', 'Nikitin'),
               'ivan123',
               '123'
          );

          $post = new Post(
              new UUID('123e4567-e89b-12d3-a456-426614174000'),
              $user,
              'title',
              'text'    
          );

          $repository->save(
              new Comment(
              new UUID('123e4567-e89b-12d3-a456-426614174000'),
              $user,
              $post,
              'commentText'    
               )
          );
     }

     public function testItThrowsAnExceptionWhenCommentNotFound() : void 
     {
          $connectionMock = $this->createStub(PDO::class);
          $statementStub = $this->createStub(PDOStatement::class);

          $statementStub->method('fetch')->willReturn(false);
          $connectionMock->method('prepare')->willReturn($statementStub);
  
          $repository = new SqliteCommentsRepository($connectionMock);
          $this->expectException(CommentNotFoundException::class);
          $this->expectExceptionMessage('Cannot get comment: a29b6ea1-5732-4bd1-86d0-455a79351a23');
  
          $repository->get(new UUID('a29b6ea1-5732-4bd1-86d0-455a79351a23'));
     }

     public function testItGetCommentByUuid() : void 
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
                    'text' => 'text',
                    'post_uuid' => 'a29b6ea1-5732-4bd1-86d0-455a79351a23',
                    'password' => '123'                    
                    
               ]
          );
          $connectionStub->method('prepare')->willReturn($statementMock);

          $commentRepository = new SqliteCommentsRepository($connectionStub);
          $comment = $commentRepository->get(new UUID('a29b6ea1-5732-4bd1-86d0-455a79351a23'));

          $this->assertSame('a29b6ea1-5732-4bd1-86d0-455a79351a23', (string)$comment->uuid());
     }


}