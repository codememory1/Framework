<?php

ini_set('display_errors', 1);

use Kernel\FrameworkBuilder;
use Symfony\Component\ErrorHandler\Debug;

require_once 'vendor/autoload.php';

//Debug::enable();

xhprof_enable(XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_CPU);
new FrameworkBuilder();

//$data = xhprof_disable();
//
//require_once '/usr/share/php/xhprof_lib/utils/xhprof_lib.php';
//require_once '/usr/share/php/xhprof_lib/utils/xhprof_runs.php';
//
//$r = new XHProfRuns_Default();
//
//$r->save_run($data, 'framework');