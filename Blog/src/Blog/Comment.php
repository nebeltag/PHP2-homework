<?php

namespace GeekBrains\LevelTwo\Blog;


class Comment
{
  private int $commentId;
  private User $commentAuthor;
  private int $commentAuthorId;
  private Post $commentedPost;
  private int $commentedPostId;
  private string $commentText;
  

  public function __construct(
     int $commentId,
     User $commentAuthor,
     Post $commentedPost,
     string $commentText
  )
  {
     $this->commentId = $commentId;
     $this->commentAuthor = $commentAuthor;
     $this->commentedPost = $commentedPost;
     $this->commentText = $commentText;
     $this->commentAuthorId = $this->commentAuthor->id();
     $this->commentedPostId = $this->commentedPost->getPostId();
  }

  public function __toString()
  {
    return $this->commentAuthor . 'пишет коммент: ' . $this->commentText;
    
  }
}