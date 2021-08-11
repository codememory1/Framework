<?php

namespace App\Controllers;

use Codememory\Components\Asset\Asset;
use Kernel\AbstractController;

/**
 * Class MainController
 *
 * @package App\Controllers
 *
 * @author  Codememory
 */
class MainController extends AbstractController
{

    /**
     * @return void
     */
    public function main(): void
    {

        $this->render('default.codememory', [
            'controller' => __CLASS__,
            'method'     => __METHOD__
        ]);

    }

}