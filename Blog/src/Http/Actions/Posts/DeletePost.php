<?php

namespace GeekBrains\LevelTwo\Http\Actions\Posts;

use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\HttpException;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\Auth\TokenAuthentificationInterface;


class DeletePost implements ActionInterface
{
    // Нам понадобится репозиторий пользователей,
    // внедряем его контракт в качестве зависимости
    public function __construct(
      private PostsRepositoryInterface $postsRepository,
      private TokenAuthentificationInterface $authentification,
    ) {
    }

    public function handle (Request $request) : Response
    {

      // Идентифицируем пользователя -
       // автора статьи
       try{
        $user = $this->authentification->user($request);
       }catch (AuthException $e){
          return new ErrorResponse($e->getMessage());
       }

      try {
        // Пытаемся получить искомый uuid коммента из запроса
        $postUuid = $request->query('uuid');
        $this->postsRepository->get(new UUID($postUuid));
        } catch (PostNotFoundException $e) {
        // Если в запросе нет параметра uuid -
        // возвращаем неуспешный ответ,
        // сообщение об ошибке берём из описания исключения
        return new ErrorResponse($e->getMessage());
        }

        
        $this->postsRepository->deletePost(new UUID($postUuid));
        
        
        // Возвращаем успешный ответ
        return new SuccessfulResponse([
        'uuid' => $postUuid        
        ]);
        }
    
  

}