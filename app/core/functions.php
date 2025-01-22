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
