<?php

namespace App\Orm\Entities;

use Codememory\Components\Database\Orm\Constructions as ORM;

/**
 * Class PhoneEntity
 *
 * @package App\Entities
 */
#[ORM\Entity(tableName: 'phones')]
class PhoneEntity
{

	/**
	 * @var mixed
	 */
    #[ORM\Column(name: 'phone', type: 'varchar', length: 20, nullable: false)]
    private mixed $phone = null;

	/**
	 * @param mixed $value
	 * @return static
	 */
    public function setPhone(mixed $value): static
    {
    
		$this->phone = $value;
		
		return $this;
    
    }

	/**
	 * @return mixed
	 */
    public function getPhone(): mixed
    {
    
		return $this->phone;
    
    }

}