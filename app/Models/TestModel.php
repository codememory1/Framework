<?php

namespace App\Models;

use Codememory\Components\Model\AbstractModel;

/**
 * Class TestModel
 *
 * @package App\Models
 */
class TestModel extends AbstractModel
{

    public function getT(): int
    {

        return 200;

    }

}