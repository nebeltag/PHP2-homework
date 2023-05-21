<?php

namespace GeekBrains\LevelTwo\Http\Actions\Posts;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Http\Auth\IdentificationInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use Psr\Log\LoggerInterface;

class CreatePost implements ActionInterface
{
   // Внедряем репозитории статей и пользователей
   public function __construct(
     private PostsRepositoryInterface $postsRepository,

     private UsersRepositoryInterface $usersRepository,

     // Вместо контракта репозитория пользователей
     // внедряем контракт идентификации
     private IdentificationInterface $identification,


     private LoggerInterface $logger
   ) {
     }

   public function handle(Request $request): Response
   {

       // Идентифицируем пользователя -
       // автора статьи
       $user = $this->identification->user($request);

    /*
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
    */
       // Генерируем UUID для новой статьи
       $newPostUuid = UUID::random();

       try {
         // Пытаемся создать объект статьи
         // из данных запроса
         $post = new Post(
         $newPostUuid,
         $user,
        //  $this->usersRepository->get($authorUuid),
         $request->jsonBodyField('title'),
         $request->jsonBodyField('text'),
         );
       } catch (HttpException $e) {
          return new ErrorResponse($e->getMessage());
         }

       // Сохраняем новую статью в репозитории
       $this->postsRepository->save($post);

       // Логируем UUID новой статьи
       $this->logger->info("Post created: $newPostUuid");

       // Возвращаем успешный ответ,
       // содержащий UUID новой статьи
       return new SuccessfulResponse([
          'uuid' => (string)$newPostUuid,
       ]);
    }
}
