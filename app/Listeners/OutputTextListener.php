<?php

namespace App\Listeners;

use Codememory\Components\Event\Interfaces\ListenerInterface;
use App\Events\OutputTextEvent;

/**
 * Class OutputTextListener
 *
 * @package App\Listeners
 */
class OutputTextListener implements ListenerInterface
{

    /**
     * @param OutputTextEvent $event
     *
     * @return void
     */
    public function handle(OutputTextEvent $event): void
    {

        echo $event->getName();

    }

}