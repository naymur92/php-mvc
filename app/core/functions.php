<?php

use App\Core\View;

/**
 * view helper file to load view from controller
 *
 * @param string $filename
 * @param array $params
 * @return void
 */
function view(string $filename, array $params): void
{
    $viewObj = new View();
    $viewObj->view($filename, $params);
}

/**
 * Print data and die
 *
 * @param mixed $data
 * @return void
 */
function dd(mixed $data): void
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";

    die;
}
