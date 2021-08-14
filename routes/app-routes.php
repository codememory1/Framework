<?php

use Codememory\Routing\Router;
use Codememory\Components\Profiling\Profiler;

/**
 *
 * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
 * Create routes below that are relevant to the application
 * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
 *
 */

Router::get('/__cdm-profiler', function () {
    if(isDev()) {
        $profiler = new Profiler();

        $profiler->connectTemplate();
    } else {
        responseCode(404);
    }
});
