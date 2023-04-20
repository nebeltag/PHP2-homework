<?php

namespace GeekBrains\LevelTwo\Blog;


class Comment
{
  // private int $commentId;
  // private User $commentAuthor;
  // private Post $commentedPost;
  // private string $commentText;
  

  public function __construct(
     private int $commentId,
     private User $commentAuthor,
     private Post $commentedPost,
     private string $commentText
  )
  {
    //  $this->commentId = $commentId;
    //  $this->commentAuthor = $commentAuthor;
    //  $this->commentedPost = $commentedPost;
    //  $this->commentText = $commentText;
     
  }

  public function __toString()
  {
    return $this->commentAuthor . 'пишет коммент: ' . $this->commentText;
    
  }

  /**
   * Get the value of commentId
   */ 
  public function getCommentId() : int
  {
    return $this->commentId;
  }

  /**
   * Set the value of commentId
   *
   * @return  self
   */ 
  public function setCommentId(int $commentId) : void
  {
    $this->commentId = $commentId;   
  }

  /**
   * Get the value of commentAuthor
   */ 
  public function getCommentAuthor() : User
  {
    return $this->commentAuthor;
  }

  /**
   * Set the value of commentAuthor
   *
   * @return  self
   */ 
  public function setCommentAuthor(User $commentAuthor) : void
  {
    $this->commentAuthor = $commentAuthor;    
  }

     /**
      * Get the value of commentedPost
      */ 
     public function getCommentedPost() : Post
     {
          return $this->commentedPost;
     }

     /**
      * Set the value of commentedPost
      *
      * @return  self
      */ 
     public function setCommentedPost(Post $commentedPost) : void
     {
          $this->commentedPost = $commentedPost;
         
     }

     /**
      * Get the value of commentText
      */ 
     public function getCommentText() : string
     {
          return $this->commentText;
     }

     /**
      * Set the value of commentText
      *
      * @return  self
      */ 
     public function setCommentText(string $commentText) : void
     {
          $this->commentText = $commentText;          
     }
}