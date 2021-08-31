<?php

namespace App\Orm\Entities;

use Codememory\Components\Database\Orm\Constructions as ORM;

/**
 * Class ProductEntity
 *
 * @package App\Entities
 */
#[ORM\Entity(tableName: 'products')]
#[ORM\Repository(repository: 'App\Repositories\ProductRepository')]
class ProductEntity
{

	/**
	 * @var mixed
	 */
    #[ORM\Column(name: 'id', type: 'int', length: null, nullable: false)]
    #[ORM\Identifier]
    #[ORM\Join(entity: PhoneEntity::class, columns: ['id'], as: ['phone'])]
    private mixed $id = null;

	/**
	 * @var mixed
	 */
    #[ORM\Column(name: 'name', type: 'varchar', length: 100, nullable: false)]
    private mixed $name = null;

	/**
	 * @var mixed
	 */
    #[ORM\Column(name: 'desc', type: 'varchar', length: 500, nullable: true)]
    private mixed $desc = null;

	/**
	 * @var mixed
	 */
    #[ORM\Column(name: 'amount', type: 'decimal', length: 10, nullable: false)]
    private mixed $amount = null;

	/**
	 * @return mixed
	 */
    public function getId(): mixed
    {
    
		return $this->id;
    
    }

	/**
	 * @param mixed $value
	 * @return static
	 */
    public function setName(mixed $value): static
    {
    
		$this->name = $value;
		
		return $this;
    
    }

	/**
	 * @return mixed
	 */
    public function getName(): mixed
    {
    
		return $this->name;
    
    }

	/**
	 * @param mixed $value
	 * @return static
	 */
    public function setDesc(mixed $value): static
    {
    
		$this->desc = $value;
		
		return $this;
    
    }

	/**
	 * @return mixed
	 */
    public function getDesc(): mixed
    {
    
		return $this->desc;
    
    }

	/**
	 * @param mixed $value
	 * @return static
	 */
    public function setAmount(mixed $value): static
    {
    
		$this->amount = $value;
		
		return $this;
    
    }

	/**
	 * @return mixed
	 */
    public function getAmount(): mixed
    {
    
		return $this->amount;
    
    }

}