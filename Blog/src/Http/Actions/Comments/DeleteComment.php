<?php

namespace GeekBrains\LevelTwo\Http\Actions\Comments;

use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\HttpException;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;


class DeleteComment implements ActionInterface
{
    // Нам понадобится репозиторий пользователей,
    // внедряем его контракт в качестве зависимости
    public function __construct(
      private CommentsRepositoryInterface $commentsRepository
    ) {
    }

    public function handle (Request $request) : Response
    {
      try {
        // Пытаемся получить искомый uuid коммента из запроса
        $commentUuid = $request->query('uuid');
        } catch (HttpException $e) {
        // Если в запросе нет параметра uuid -
        // возвращаем неуспешный ответ,
        // сообщение об ошибке берём из описания исключения
        return new ErrorResponse($e->getMessage());
        }

        try {
        // Пытаемся удалить коммент из репозитория
        $comment = $this->commentsRepository->deleteComment(new UUID($commentUuid));
        } catch (CommentNotFoundException $e) {
        // Если коммент не найден -
        // возвращаем неуспешный ответ
        return new ErrorResponse($e->getMessage());
        }

        
        // Возвращаем успешный ответ
        // return new SuccessfulResponse([
        // 'authorUuid' => $post->getPostAuthor(),
        // 'title' => $post->getPostTitle(),
        // 'text' => $post->getPostText()
        // ]);
        }
    
  

}