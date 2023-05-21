<?php

namespace GeekBrains\LevelTwo\Http\Actions\Likes;

use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\HttpException;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\LikeNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepository\LikesRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use Psr\Log\LoggerInterface;


class FindByPostUuid implements ActionInterface
{
    // Нам понадобится репозиторий лайков,
    // внедряем его контракт в качестве зависимости
    public function __construct(
      private LikesRepositoryInterface $likesRepository,
      private LoggerInterface $logger
    ) {
    }

    public function handle (Request $request) : Response
    {
      
      $this->logger->info("Find likes started");

      try {
        // Пытаемся получить искомый uuid статьи из запроса
        $postUuid = $request->query('uuid');
        } catch (HttpException $e) {
        // Если в запросе нет параметра uuid -
        // возвращаем неуспешный ответ,
        // сообщение об ошибке берём из описания исключения
        return new ErrorResponse($e->getMessage());
        }

        try {
        // Пытаемся найти статью в репозитории
        $likes = $this->likesRepository->getByPostUuid(new UUID($postUuid));
        } catch (LikeNotFoundException $e) {
        // Если статья не найдена -
        // возвращаем неуспешный ответ
        $this->logger->warning("No any likes found to post: $postUuid");
        return new ErrorResponse($e->getMessage());
        }

       
       $response = [];
        
        foreach($likes as $el => $like){
          // $likeUuid = (string)$like->uuid();
          // $postUuid = (string)$like->getLikedPost();
          // $authorUuid = (string)$like->getLikeAuthor();
          $response []= 
          ["uuid" => (string)$like->uuid(),
          'post_uuid' => (string)$like->getLikedPost(),
          'author_uuid' => (string)$like->getLikeAuthor()];
        }

        // Возвращаем успешный ответ
          return new SuccessfulResponse(         
          [  $response      
          ]);
      
      
      

      
    }
    
  

}