<?php

namespace AuthorizedUpdates\Entity;

use Ale\Entities\IdentifiedEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 * @ORM\Entity
 *
 * @method setAccess(string $access)
 * @method string getAccess()
 *
 * @method AuthorizedUpdateItem[] getItems()
 *
 * @method setExpire(\DateTime $expire)
 * @method \DateTime getExpire()
 *
 * @method setCreated(\DateTime $created)
 * @method \DateTime getCreated()
 *
 * @method \DateTime getExecuted()
 * @method setExecuted(\DateTime $executed)
 *
 */
class AuthorizedUpdate extends IdentifiedEntity {


	/**
	 * @ORM\Column(type="string", unique=true, length=40)
	 * @var string
	 */
	protected $access;


	/**
	 * @ORM\OneToMany(targetEntity="AuthorizedUpdateItem", mappedBy="group")
	 * @var AuthorizedUpdateItem[]
	 */
	protected $items;


	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 * @var \DateTime
	 */
	protected $expire;


	/**
	 * @ORM\Column(type="datetime")
	 * @var \DateTime
	 */
	protected $created;


	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 * @var \DateTime
	 */
	protected $executed;


	public function __construct()
	{
		$this->created = new \DateTime();
		$this->items = new ArrayCollection();
	}

}