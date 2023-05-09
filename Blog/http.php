<?php

use GeekBrains\LevelTwo\Http\Request;

require_once __DIR__ . '/vendor/autoload.php';

$request = new Request($_GET, $_SERVER);

$path = $request->path();
$name = $request->query('name');
$header = $request->header('cookie');


echo "$path\n";
echo "$name\n";
echo "$header";
