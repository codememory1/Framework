<?php

ini_set('display_errors', 1);

use Kernel\FrameworkBuilder;

require_once 'vendor/autoload.php';

//xhprof_enable(XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_CPU);

try {

    new FrameworkBuilder();

} catch (ErrorException $e) {
    die($e->getMessage());
}

//
//$xhprof_data = xhprof_disable();
//
//require_once '/usr/share/php/xhprof_lib/utils/xhprof_lib.php';
//require_once '/usr/share/php/xhprof_lib/utils/xhprof_runs.php';
//
//$xhprof = new XHProfRuns_Default();
//$run_id = $xhprof->save_run($xhprof_data, "my_application");