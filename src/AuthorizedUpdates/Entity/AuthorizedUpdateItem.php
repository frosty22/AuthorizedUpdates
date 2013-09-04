<?php

namespace AuthorizedUpdates\Entity;

use Ale\Entities\IdentifiedEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 * @ORM\Entity
 *
 * @method int getPrimaryKey()
 * @method setPrimaryKey(int $key)
 *
 * @method string getEntity()
 * @method setEntity(string $entity)
 *
 * @method string getProperty()
 * @method setProperty(string $property)
 *
 * @method string getValue()
 * @method setValue(string $value)
 *
 * @method string getOld()
 * @method setOld(string $old)
 *
 * @method AuthorizedUpdate getGroup()
 * @method setGroup(AuthorizedUpdate $group)
 *
 */
class AuthorizedUpdateItem extends IdentifiedEntity {


	/**
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	protected $primaryKey;


	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $entity;


	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $property;


	/**
	 * @ORM\Column(type="text", nullable=true)
	 * @var string
	 */
	protected $old;


	/**
	 * @ORM\Column(type="text", nullable=true)
	 * @var string
	 */
	protected $value;


	/**
	 * @ORM\ManyToOne(targetEntity="AuthorizedUpdate", inversedBy="items")
	 * @var AuthorizedUpdate
	 */
	protected $group;

}