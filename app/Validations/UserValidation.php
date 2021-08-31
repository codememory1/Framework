<?php

namespace App\Validations;

use Codememory\Components\Validator\Interfaces\ValidateInterface;
use Codememory\Components\Validator\Interfaces\ValidationBuildInterface;
use Codememory\Components\Validator\Interfaces\ValidatorInterface;

/**
 * Class UserValidation
 *
 * @package App\Validations
 */
class UserValidation implements ValidationBuildInterface
{

    /**
     * @inheritDoc
     */
    public function build(ValidatorInterface $validator, ...$args): void
    {

        $validator
            ->addValidation('name', function (ValidateInterface $validate) {
                $validate
                    ->addRule('string', 'name_string')
                    ->addRule('min:2', 'name_min')
                    ->addRule('max:10', 'name_max')
                    ->addMessage('Имя должно быть строкой', 'name_string')
                    ->addMessage('Имя должно состовлять минимум 2 символа', 'name_min')
                    ->addMessage('Имя не должно превышать 10 символов', 'name_max');

                return $validate;
            })
            ->addValidation('age', function (ValidateInterface $validate) {
                $validate
                    ->addRule('number', 'age_numeric')
                    ->addMessage('Возврост должен быть числом', 'age_numeric');

                return $validate;
            });

    }

}