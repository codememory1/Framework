<?php

use Codememory\Routing\Router;
use App\Controllers\MainController;

/**
 *
 * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
 * Create routes below that are relevant to the application
 * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
 *
 */

Router::get('/', [MainController::class, 'main'])->name('index');