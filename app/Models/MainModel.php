<?php

namespace App\Models;

use App\Events\OutputTextEvent;
use Codememory\Components\Model\AbstractModel;

/**
 * Class MainModel
 *
 * @package App\Models
 */
class MainModel extends AbstractModel
{

    public function test()
    {

        $this->dispatchEvent(OutputTextEvent::class, ['Danil']);

    }

}