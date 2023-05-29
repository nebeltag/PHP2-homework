<?php

namespace GeekBrains\LevelTwo\Http\Actions\Users;

use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\http\Actions\ActionInterface;
use GeekBrains\LevelTwo\http\ErrorResponse;
use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\http\Response;
use GeekBrains\LevelTwo\http\SuccessfulResponse;
use GeekBrains\LevelTwo\Person\Name;
use Psr\Log\LoggerInterface;

class CreateUser implements ActionInterface
{

   public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger
   ) {

   }
    
   public function handle(Request $request): Response
    {   
        $this->logger->info("Create user started");

        try {
            $username = $request->jsonBodyField('username');
          } catch (HttpException | InvalidArgumentException $e) {
             return new ErrorResponse($e->getMessage());
           }

        // if ($this->userExists($username)) {

        //     $this->logger->warning("User already exists: $username");        
        //     throw new CommandException("User already exists: $username");
        //     }
        
        $password = $request->jsonBodyField('password');

        try {
            // $newUserUuid = UUID::random();            
            $user = User::createFrom( 
            $username,
            $password,         
            new Name(               
               $request->jsonBodyField('first_name'),
               $request->jsonBodyField('last_name'),               
               ),
            
                
            );

        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());

        }

        $this->usersRepository->save($user);

        $this->logger->info("User created: ");

        // return new SuccessfulResponse([
        //     'uuid' => (string)$newUserUuid,
        // ]);
    }

    private function userExists(string $username): bool
    {
      try {
      // Пытаемся получить пользователя из репозитория
      $this->usersRepository->getByUsername($username);
      } catch (UserNotFoundException) {
      return false;
      }
      return true;
    }
}