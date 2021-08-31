<?php

use Codememory\Routing\Router;

/**
 *
 * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
 * Create routes below that are relevant to the application
 * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
 *
 */

Router::get('test', 'App\Controllers\TestController#test');
