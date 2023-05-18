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


class FindByPostUuid implements ActionInterface
{
    // Нам понадобится репозиторий лайков,
    // внедряем его контракт в качестве зависимости
    public function __construct(
      private LikesRepositoryInterface $likesRepository
    ) {
    }

    public function handle (Request $request) : Response
    {
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
        return new ErrorResponse($e->getMessage());
        }

       
       $response = [];
        
        foreach($likes as $el => $like){
          $likeUuid = $like->uuid()->__toString();
          $postUuid = $like->getLikedPost()->__toString();
          $authorUuid = $like->getLikeAuthor()->__toString();
          $response []= 
          ["uuid" => "$likeUuid",
          'post_uuid' => "$postUuid",
          'author_uuid' => "$authorUuid"];
        }

        // Возвращаем успешный ответ
          return new SuccessfulResponse(         
          [  $response      
          ]);
      
      
      

      
    }
    
  

}