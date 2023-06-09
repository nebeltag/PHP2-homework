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
use GeekBrains\LevelTwo\Http\Auth\TokenAuthentificationInterface;


class DeleteLike implements ActionInterface
{
    // Нам понадобится репозиторий пользователей,
    // внедряем его контракт в качестве зависимости
    public function __construct(
      private LikesRepositoryInterface $likesRepository,
      private TokenAuthentificationInterface $authentification
    ) {
    }

    public function handle (Request $request) : Response
    {

      //Аутентифицируем юзера
      try{
        $authorUuid = $this->authentification->user($request)->uuid();      
      }catch(AuthException $e){
        return new ErrorResponse($e->getMessage());
      }

      try {
        // Пытаемся получить искомый uuid коммента из запроса
        $likeUuid = $request->query('uuid');
        $this->likesRepository->get(new UUID($likeUuid));
        } catch (PostNotFoundException $e) {
        // Если в запросе нет параметра uuid -
        // возвращаем неуспешный ответ,
        // сообщение об ошибке берём из описания исключения
        return new ErrorResponse($e->getMessage());
        }

        
        $this->likesRepository->deleteLike(new UUID($likeUuid));
        
        
        // Возвращаем успешный ответ
        return new SuccessfulResponse([
        'uuid' => $likeUuid        
        ]);
        }
    
  

}