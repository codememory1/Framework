<?php

namespace App\Controllers;

use App\Entities\ProductEntity;
use App\Models\MainModel;
use Codememory\Components\Logging\Logging;
use Codememory\Components\Mail\Interfaces\MailerInterface;
use Codememory\Components\Mail\Interfaces\MailerPackInterface;
use Codememory\Components\Mail\Interfaces\MessageInterface;
use Codememory\Components\View\Interfaces\ViewInterface;
use Kernel\AbstractController;

/**
 * Class TestController
 *
 * @package App\Controllers
 */
class TestController extends AbstractController
{

    public function test(): void
    {

        /** @var Logging $logging */
        $logging = $this->get('logging');

        $logging->createLogger('createUser')->error('csdcsdc');

//        die;
        /** @var MainModel $mainModel */
        $mainModel = $this->getModel('Main');

        $mainModel->test();

    }

    /**
     * @return bool
     */
    public function web(): bool
    {

        /** @var ViewInterface $view */
        $view = $this->get('view');

        $this->render('red.html.twig', ['age' => 18, 'name' => 'Danil']);

        return true;

    }

    /**
     * @return bool
     */
    public function home(): bool
    {

        $this->get('response')
            ->setContent('Hello, TestController -> home')->sendContent();

        return true;

    }

}
