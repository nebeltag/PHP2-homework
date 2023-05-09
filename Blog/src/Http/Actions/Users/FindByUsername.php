<?php

namespace GeekBrains\LevelTwo\Http\Actions\Users;

use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\HttpException;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;

class FindByUserName implements ActionInterface
{
    // Нам понадобится репозиторий пользователей,
    // внедряем его контракт в качестве зависимости
    public function __construct(
      private UsersRepositoryInterface $usersRepository
    ) {
    }

    public function handle (Request $request) : Response
    {
      try {
        // Пытаемся получить искомое имя пользователя из запроса
        $username = $request->query('username');
        } catch (HttpException $e) {
        // Если в запросе нет параметра username -
        // возвращаем неуспешный ответ,
        // сообщение об ошибке берём из описания исключения
        return new ErrorResponse($e->getMessage());
        }

        try {
        // Пытаемся найти пользователя в репозитории
        $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
        // Если пользователь не найден -
        // возвращаем неуспешный ответ
        return new ErrorResponse($e->getMessage());
        }
        // Возвращаем успешный ответ
        return new SuccessfulResponse([
        'username' => $user->getUsername(),
        'name' => $user->name()->getFirstName() . ' ' . $user->name()->getLastName(),
        ]);
        }
    
  

}