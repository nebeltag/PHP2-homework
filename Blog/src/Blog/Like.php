<?php

namespace GeekBrains\LevelTwo\Blog;


class Like
{
  
  public function __construct(
     private UUID $likeId,
     private string $likedPost,
     private string $likeAuthor    
  )
  {
         
  }

  public function __toString()
  {
    return $this->likeAuthor . ' лайкает статью ' . $this->likedPost;
    
  }

  /**
   * Get the value of likeId
   */ 
  public function uuid() : UUID
  {
    return $this->likeId;
  }

  /**
   * Set the value of likeId
   *
   * @return  self
   */ 
  // public function setCommentId(int $commentId) : void
  // {
  //   $this->commentId = $commentId;   
  // }

  /**
   * Get the value of likeAuthor
   */ 
  public function getLikeAuthor() : string
  {
    return $this->likeAuthor;
  }

  /**
   * Set the value of likeAuthor
   *
   * @return  self
   */ 
  public function setLikeAuthor(string $likeAuthor) : void
  {
    $this->likeAuthor = $likeAuthor;    
  }

     /**
      * Get the value of likedPost
      */ 
     public function getLikedPost() : string
     {
          return $this->likedPost;
     }

     /**
      * Set the value of likedPost
      *
      * @return  self
      */ 
     public function setLikedPost(string $likedPost) : void
     {
          $this->likedPost = $likedPost;
         
     }
    
}