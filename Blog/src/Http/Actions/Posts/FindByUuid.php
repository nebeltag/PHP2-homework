<?php

namespace GeekBrains\LevelTwo\Http\Actions\Posts;

use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\HttpException;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use Psr\Log\LoggerInterface;


class FindByUuid implements ActionInterface
{
    // Нам понадобится репозиторий статей,
    // внедряем его контракт в качестве зависимости
    public function __construct(
      private PostsRepositoryInterface $postsRepository,
      private LoggerInterface $logger
    ) {
    }

    public function handle (Request $request) : Response
    { 

      $this->logger->info("Find post started");

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
        $post = $this->postsRepository->get(new UUID($postUuid));
        } catch (PostNotFoundException $e) {
        // Если статья не найдена -
        // возвращаем неуспешный ответ
        $this->logger->warning("Cannot get post : $postUuid");
        return new ErrorResponse($e->getMessage());
        }

        
        // Возвращаем успешный ответ
        return new SuccessfulResponse([
        'authorUuid' => $post->getPostAuthor(),
        'title' => $post->getPostTitle(),
        'text' => $post->getPostText()
        ]);
        }
    
  

}