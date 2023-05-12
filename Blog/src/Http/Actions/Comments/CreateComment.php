<?php

namespace GeekBrains\LevelTwo\Http\Actions\Comments;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;

class CreateComment implements ActionInterface
{
   // Внедряем репозитории статей, пользователей и комментов
   public function __construct(
     private CommentsRepositoryInterface $commentsRepository,
     private UsersRepositoryInterface $usersRepository,
     private PostsRepositoryInterface $postsRepository,         

   ) {
     }

   public function handle(Request $request): Response
   {
       // Пытаемся создать UUID пользователя из данных запроса
       try {
         $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
       } catch (HttpException | InvalidArgumentException $e) {
          return new ErrorResponse($e->getMessage());
        }

       // Пытаемся найти пользователя в репозитории
       try {
          $this->usersRepository->get($authorUuid);
       } catch (UserNotFoundException $e) {
          return new ErrorResponse($e->getMessage());
         }

       // Пытаемся создать UUID статьи из данных запроса
        try {
           $postUuid = new UUID($request->jsonBodyField('post_uuid'));
         } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
          }  

         // Пытаемся найти статью в репозитории
       try {
        $this->postsRepository->get($postUuid);
     } catch (UserNotFoundException $e) {
        return new ErrorResponse($e->getMessage());
       }

       // Генерируем UUID для нового коммента
       $newCommentUuid = UUID::random();

       try {
         // Пытаемся создать объект статьи
         // из данных запроса
         $comment = new Comment(
         $newCommentUuid,
         $this->usersRepository->get($authorUuid),
         $this->postsRepository->get($postUuid),
         $request->jsonBodyField('text'),
         );
       } catch (HttpException $e) {
          return new ErrorResponse($e->getMessage());
         }

       // Сохраняем новую статью в репозитории
       $this->commentsRepository->save($comment);

       // Возвращаем успешный ответ,
       // содержащий UUID новой статьи
       return new SuccessfulResponse([
          'uuid' => (string)$newCommentUuid,
       ]);
    }
}


