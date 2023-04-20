<?php

namespace GeekBrains\LevelTwo\Blog;

class Post 
{
  private int $postId;
  private User $postAuthor;
  private string $postTitle;
  private string $postText;
  

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
     
  }

  public function __toString()
  {
    return $this->postAuthor . 'пишет: ' . PHP_EOL . $this->postTitle . PHP_EOL . 
    '>>>' . $this->postText . PHP_EOL;
    
  }  

  /**
   * Get the value of postId
   */ 
  public function getPostId() : int
  {
    return $this->postId;
  }

  /**
   * Set the value of postId
   *
   * @return  self
   */ 
  public function setPostId(int $postId) : void
  {
    $this->postId = $postId;
    
  }

  
  /**
   * Get the value of postAuthor
   */ 
  public function getPostAuthor() : User
  {
    return $this->postAuthor;
  }

  /**
   * Set the value of postAuthor
   *
   * @return  self
   */ 
  public function setPostAuthor(User $postAuthor) : void
  {
    $this->postAuthor = $postAuthor;
    
  }

  /**
   * Get the value of postTitle
   */ 
  public function getPostTitle() : string
  {
    return $this->postTitle;
  }

  /**
   * Set the value of postTitle
   *
   * @return  self
   */ 
  public function setPostTitle(string $postTitle) : void
  {
    $this->postTitle = $postTitle;    
  }

  /**
   * Get the value of postText
   */ 
  public function getPostText() : string
  {
    return $this->postText;
  }

  /**
   * Set the value of postText
   *
   * @return  self
   */ 
  public function setPostText(string $postText) : void
  {
    $this->postText = $postText;
    
  }
}