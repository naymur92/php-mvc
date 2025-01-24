<?php

use App\Core\App;
use App\Core\Container;
use App\Core\DB;

$container = new Container();

$container->bind('App\Core\DB', function () {
    return DB::getInstance();
});

App::setContainer($container);
