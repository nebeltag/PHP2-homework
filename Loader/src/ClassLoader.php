<?php

namespace Doctrine\Common;

class ClassLoader 
{
  private int $id;

  public function __construct(int $id)
  {
    $this->id = $id;
  }
}