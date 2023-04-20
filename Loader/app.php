<?php

use Doctrine\Common\ClassLoader;
use my\package\Class_Name;

spl_autoload_register(
  function ($className)
  {
    $file = $className . ".php";
    // $file = "/some/path/" . $file;
    $file = str_replace(["\\", "_"], "/", $file);
    $file = str_replace(["Doctrine/Common/", "my/package/Class/"], "src/", $file);
    var_dump($file);
    if(file_exists($file))
    {
      include $file;
    }
  });

  $loader = new ClassLoader(5);
  $name = new Class_Name (6);