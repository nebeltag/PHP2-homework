<?php

namespace GeekBrains\LevelTwo\Blog;

class Post 
{
  private int $postId;
  private User $postAuthor;
  private string $postTitle;
  private string $postText;
  private int $postAuthorId;

  public function __construct(
     int $postId,
     User $postAuthor,
     string $postTitle,
     string $postText
  )
  {
     $this->postId = $postId;
     $this->postAuthor = $postAuthor;
     $this->postTitle = $postTitle;
     $this->postText = $postText;
     $this->postAuthorId = $this->postAuthor->id();
  }

  // public function getPostAuthorId () : int
  // {
  //    return $this->postAuthor->id();
  // }

  /**
   * Get the value of postId
   */ 
  public function getPostId()
  {
    return $this->postId;
  }

  public function __toString()
  {
    return $this->postAuthor . 'пишет: ' . PHP_EOL . $this->postTitle . PHP_EOL . 
    '>>>' . $this->postText . PHP_EOL;
    
  }

  
}