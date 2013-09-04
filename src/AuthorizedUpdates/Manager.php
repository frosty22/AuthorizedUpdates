<?php

namespace AuthorizedUpdates;

use Ale\Entities\IdentifiedEntity;
use Ale\Entities\BaseEntity;
use AuthorizedUpdates\Entity\AuthorizedUpdate;
use AuthorizedUpdates\Entity\AuthorizedUpdateItem;
use Kdyby\Doctrine\EntityManager;


/**
 *
 * Save / execute manager.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class Manager extends \Nette\Object {


	/**
	 * @var EntityManager
	 */
	private $em;


	/**
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	/**
	 * @param string $access
	 * @return AuthorizedUpdateItem[]
	 */
	public function getUpdateItem($access)
	{
		$authorizeUpdateDao = $this->em->getRepository('AuthorizedUpdates\Entity\AuthorizedUpdate');
		$group = $authorizeUpdateDao->findOneBy(array("access" => $access));

		if (!$group)
			return NULL;

		/** @var AuthorizedUpdate $group */

		if ($group->getExpire() && $group->getExpire() < new \DateTime())
			return NULL;

		if ($group->getExecuted())
			return NULL;

		return $group;
	}


	/**
	 * Process access key and return count of changes
	 * @param string $access
	 * @return int
	 */
	public function process($access)
	{
		$updateItem = $this->getUpdateItem($access);
		if (!$updateItem)
			return 0;

		$count = 0;
		foreach ($updateItem->getItems() as $update) {
			if ($this->execute($update)) {
				$count++;
			}
		}

		$updateItem->setExecuted(new \DateTime());
		$this->em->flush();

		return $count;
	}


	/**
	 * @param IdentifiedEntity $entity
	 * @param string|\DateTime|null $expire
	 * @return string|NULL
	 */
	public function save(IdentifiedEntity $entity, $expire = NULL)
	{
		$changes = $this->getChanges($entity);
		if (count($changes) == 0) return NULL;

		$access = $this->generateAccess();

		$group = new AuthorizedUpdate();
		$group->setAccess($access);
		$group->setExpire($expire ? \Nette\DateTime::from($expire) : NULL);

		$entityName = $entity->getClassName();

		$authorizeUpdateItemDao = $this->em->getRepository('AuthorizedUpdates\Entity\AuthorizedUpdateItem');

		foreach ($changes as $name => $values) {
			$item = new AuthorizedUpdateItem();
			$item->setPrimaryKey($entity->id);
			$item->setGroup($group);
			$item->setEntity($entityName);
			$item->setProperty($name);
			$item->setOld($values[0]);
			$item->setValue($values[1]);
			$authorizeUpdateItemDao->add($item);
		}

		$authorizeUpdateDao = $this->em->getRepository('AuthorizedUpdates\Entity\AuthorizedUpdate');

		$this->em->detach($entity);
		$authorizeUpdateDao->save($group);
		$this->em->persist($entity);

		return $access;
	}


	/**
	 * Get changed cols on entity
	 * @param BaseEntity $entity
	 * @return array
	 */
	protected function getChangedCols(BaseEntity $entity)
	{
		return array_keys($this->getChanges($entity));
	}


	/**
	 * Get changes
	 * @param BaseEntity $entity
	 * @return array
	 */
	protected function getChanges(BaseEntity $entity)
	{
		$uow = $this->em->getUnitOfWork();
		$uow->computeChangeSets();
		return $uow->getEntityChangeSet($entity);
	}


	/**
	 * Generate random access string
	 * @return string
	 */
	protected function generateAccess()
	{
		return sha1(rand() . time() . "$+$.");
	}


	/**
	 * Execute update item row on entity
	 * @param AuthorizedUpdateItem $update
	 * @return bool
	 */
	protected function execute(AuthorizedUpdateItem $update)
	{
		$entity = $this->em->getRepository($update->getEntity())->find($update->getPrimaryKey());
		if ($entity) {
			$entity->{$update->getProperty()} = $update->getValue();
			return TRUE;
		}
		return FALSE;
	}

}