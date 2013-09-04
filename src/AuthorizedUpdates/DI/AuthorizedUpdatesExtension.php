<?php

namespace AuthorizedUpdates\DI;

use Kdyby\Doctrine\DI\IEntityProvider;
use Nette\Config\CompilerExtension;

/**
 * Authorized updates extension.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class AuthorizedUpdatesExtension extends CompilerExtension
	implements IEntityProvider {


	/**
	 * Load configuration
	 */
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix("manager"))
			->setClass('AuthorizedUpdates\Manager');
	}


	/**
	 * @return array
	 */
	public function getEntityMappings()
	{
		return array(
			'AuthorizedUpdates\Entity' => __DIR__ . '/../Entity'
		);
	}

}