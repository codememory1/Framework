<?php

namespace App\Events;

use Codememory\Components\Event\Interfaces\EventInterface;
use App\Listeners\OutputTextListener;

/**
 * Class OutputTextEvent
 *
 * @package App\Events
 */
class OutputTextEvent implements EventInterface
{

    /**
     * @var string
     */
    private string $name;

    /**
     * OutputTextEvent Construct
     */
    public function __construct(string $name)
    {

        $this->name = $name;

    }

    /**
     * @inheritDoc
     */
    public function getListeners(): array
    {

        return [
            OutputTextListener::class
        ];

    }

    /**
     * @return string
     */
    public function getName(): string
    {

        return $this->name;

    }

}