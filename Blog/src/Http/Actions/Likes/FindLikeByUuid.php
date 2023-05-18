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


class FindLikeByUuid implements ActionInterface
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
        $likeUuid = $request->query('uuid');
        } catch (HttpException $e) {
        // Если в запросе нет параметра uuid -
        // возвращаем неуспешный ответ,
        // сообщение об ошибке берём из описания исключения
        return new ErrorResponse($e->getMessage());
        }

        try {
        // Пытаемся найти статью в репозитории
        $like = $this->likesRepository->get(new UUID($likeUuid));
        } catch (LikeNotFoundException $e) {
        // Если статья не найдена -
        // возвращаем неуспешный ответ
        return new ErrorResponse($e->getMessage());
        }

        
        // Возвращаем успешный ответ
        return new SuccessfulResponse([
        'post_uuid' => $like->getLikedPost(),
        'author_uuid' => $like->getLikeAuthor(),
        ]);
    }
    
  

}