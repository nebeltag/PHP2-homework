<?php

namespace GeekBrains\LevelTwo\Blog;


class Like
{
  
  public function __construct(
     private UUID $likeId,
     private UUID $likedPost,
     private UUID $likeAuthor    
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
  public function getLikeAuthor() : UUID
  {
    return $this->likeAuthor;
  }

  /**
   * Set the value of likeAuthor
   *
   * @return  self
   */ 
  public function setLikeAuthor(UUID $likeAuthor) : void
  {
    $this->likeAuthor = $likeAuthor;    
  }

     /**
      * Get the value of likedPost
      */ 
     public function getLikedPost() : UUID
     {
          return $this->likedPost;
     }

     /**
      * Set the value of likedPost
      *
      * @return  self
      */ 
     public function setLikedPost(UUID $likedPost) : void
     {
          $this->likedPost = $likedPost;
         
     }
    
}