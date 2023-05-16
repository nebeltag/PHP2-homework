<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\LikesRepository;

use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Blog\UUID;

interface LikesRepositoryInterface
{
public function save(Like $like): void;
public function deleteLike(UUID $uuid): void;
public function getByPostUuid(string $uuid): Like;
public function getByPostAndAuthor(Like $like): bool;
// public function getByPostUuid();
}