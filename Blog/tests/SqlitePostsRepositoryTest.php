<?php

namespace GeekBrains\LevelTwo;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqlitePostsRepositoryTest extends TestCase 
{
     public function testItSavesPostToDataBase() : void 
     {
          $connectionStub = $this->createStub(PDO::class);
          $statementMock = $this->createMock(PDOStatement::class);

          //TODO Настроить стабы и моки

          $repository = new SqlitePostsRepository($connectionStub);

          new User( 
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

     }

     public function testItGetPostByUuid() : void 
     {

     }


}