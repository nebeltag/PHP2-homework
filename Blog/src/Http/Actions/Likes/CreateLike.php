<?php

namespace GeekBrains\LevelTwo\Http\Actions\Likes;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepository\LikesRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Exceptions\LikeNotFoundException;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Like;


class CreateLike implements ActionInterface
{
   // Внедряем репозиторий лайков
   public function __construct(
     private LikesRepositoryInterface $likesRepository,
     
   ) {
     }

   public function handle(Request $request): Response
   {

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

       // Генерируем UUID для нового лайка
       $newLikeUuid = UUID::random();

       try {
         // Пытаемся создать объект лайка
         // из данных запроса
         $like = new Like(
         $newLikeUuid,
         $request->jsonBodyField('post_uuid'),
         $request->jsonBodyField('author_uuid'),
         );
       } catch (HttpException $e) {
          return new ErrorResponse($e->getMessage());
         }
       
        //  try{
        //   $this->likesRepository->getByPostAndAuthor($like);
        //  }catch (HttpException $e) {
        //   return new ErrorResponse($e->getMessage());
        //  }

       // Сохраняем новую статью в репозитории
       $this->likesRepository->save($like);

       // Возвращаем успешный ответ,
       // содержащий UUID новой статьи
       return new SuccessfulResponse([
          'uuid' => (string)$newLikeUuid,
       ]);
    }
}
