<?php

namespace GeekBrains\LevelTwo\Http\Actions\Likes;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\AuthException;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepository\LikesRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Exceptions\LikeAllReadyExists;
use GeekBrains\LevelTwo\Http\Auth\TokenAuthentificationInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Like;
use Psr\Log\LoggerInterface;


class CreateLike implements ActionInterface
{
   // Внедряем репозиторий лайков
   public function __construct(
     private LikesRepositoryInterface $likesRepository,
     private PostsRepositoryInterface $postsRepository,
     private TokenAuthentificationInterface $authentification,
     private LoggerInterface $logger
     
   ) {
     }

   public function handle(Request $request): Response
   {

    $this->logger->info("Create like started");

       // Пытаемся получить данные из запроса
      try {
        $postUuid = $request->jsonBodyField('post_uuid');
        // $authorUuid = $request->jsonBodyField('author_uuid');
      } catch (HttpException $e) {
        return new ErrorResponse($e->getMessage());
       }
      //Проверяем uuid статьи
      try{
        $this->postsRepository->get(new UUID($postUuid));
      }catch(PostNotFoundException $e){
        return new ErrorResponse($e->getMessage());
      }

      //Аутентифицируем юзера
      try{
        $authorUuid = $this->authentification->user($request)->uuid();      
      }catch(AuthException $e){
        return new ErrorResponse($e->getMessage());
      }

      // try{
      //   $this->usersRepository->get(new UUID($authorUuid));
      // }catch(UserNotFoundException $e){
      //   return new ErrorResponse($e->getMessage());
      // }

      // try{
      //   $this->usersRepository->get(new UUID($authorUuid));
      // }catch(UserNotFoundException $e){
      //   return new ErrorResponse($e->getMessage());
      // }

      //Проверяем наличие лайка автора к статье
      try {
       $this->likesRepository->checkUserLikeForPostExists($postUuid, $authorUuid);
      } catch (LikeAllReadyExists $e) {
        return new ErrorResponse($e->getMessage());
       }

       // Генерируем UUID для нового лайка
       $newLikeUuid = UUID::random();

       
      //Создаем объект лайка из данных запроса
      
      $like = new Like(
      $newLikeUuid,
      new UUID($postUuid),
      new UUID($authorUuid),
      );
       
       
        
       // Сохраняем новый лайк в репозитории
       $this->likesRepository->save($like);

       $this->logger->info("Like created: $newLikeUuid");

       // Возвращаем успешный ответ,
       // содержащий UUID новой статьи
       return new SuccessfulResponse([
          'uuid' => (string)$newLikeUuid,
       ]);
    }
}
