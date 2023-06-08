<?php

namespace GeekBrains\LevelTwo\Blog\Commands\FakeData;

use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Person\Name;
use Symfony\Component\Console\Command\Command;
use Faker\Generator;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

class PopulateDB extends Command
{
    public function __construct(
        private Generator $faker,
        private UsersRepositoryInterface $usersRepository,
        private PostsRepositoryInterface $postsRepository,
        private CommentsRepositoryInterface $commentsRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data')

            ->addOption(
              // Имя опции
                  'users-number',
                  // Сокращённое имя
                  'u',
                  // Опция имеет значения
                  InputOption::VALUE_OPTIONAL,
                  // Описание
                  'Number of users',
              )
              ->addOption(
                  'posts-number',
                  'p',
                  InputOption::VALUE_OPTIONAL,
                  'Number of posts',
              )
              ->addOption(
                'comments-number',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Number of comments',
            );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {

        // Получаем значения опций
        $usersNumber = $input->getOption('users-number');
        $postsNumber = $input->getOption('posts-number');
        $commentsNumber = $input->getOption('comments-number');

        // Задаем значения опций по умолчанию
        if (empty($usersNumber) && empty($postsNumber) && empty($postsNumber)) {
            // $output->writeln('Nothing to update');
            // return Command::SUCCESS;
            $usersNumber = 2;
            $postsNumber = 2;
            $commentsNumber = 1;
        }

        // Создаём пользователей
        $users = [];
        for ($i = 0; $i < $usersNumber; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->getUsername());
        }

        // От имени каждого пользователя
        // создаём статьи
        $posts = [];
        foreach ($users as $user) {
            for ($i = 0; $i < $postsNumber; $i++) {
                $post = $this->createFakePost($user);
                $posts[] = $post;
                $output->writeln('Post created: ' . $post->getPostTitle());
            }
        }

        foreach ($posts as $post) {
            for ($i = 0; $i < $commentsNumber; $i++) {
                $comment = $this->createFakeComment($post);
                $output->writeln('Comment created: ' . $comment->getCommentText());
              }
            } 


        return Command::SUCCESS;
    }

    private function createFakeUser(): User
    {
        $user = User::createFrom(
        // Генерируем имя пользователя
            $this->faker->userName,
            // Генерируем пароль
            $this->faker->password,
            new Name(
            // Генерируем имя
                $this->faker->firstName,
                // Генерируем фамилию
                $this->faker->lastName
            )
        );

        // Сохраняем пользователя в репозиторий
        $this->usersRepository->save($user);
        return $user;
    }

    private function createFakePost(User $author): Post
    {
        $post = new Post(
            UUID::random(),
            $author,
            // Генерируем предложение не длиннее шести слов
            $this->faker->sentence(6, true),
            // Генерируем текст
            $this->faker->realText
        );

        // Сохраняем статью в репозиторий
        $this->postsRepository->save($post);
        return $post;
    }

    private function createFakeComment(Post $post): Comment
    {
        $comment = new Comment(
            UUID::random(),
            $post->getPostAuthor(),
            $post,            
            // Генерируем предложение не длиннее шести слов
            $this->faker->sentence(6, true),            
        );

        // Сохраняем коммент в репозиторий
        $this->commentsRepository->save($comment);
        return $comment;
    }
}