<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Initializer;

use Zend\ServiceManager\AbstractPluginManager,
    Zend\ServiceManager\InitializerInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    Doctrine\ORM\EntityManager,
    DoctrineModule\Persistence\ObjectManagerAwareInterface;

class EntityManagerInitializer implements InitializerInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function initialize($instance, ServiceLocatorInterface $serviceLocator)
	{
        if ($instance instanceof ObjectManagerAwareInterface) {
            if ($serviceLocator instanceof AbstractPluginManager) {
            	$serviceLocator = $serviceLocator->getServiceLocator();
            }

            $objectManager = $serviceLocator->get(EntityManager::class);
            $instance->setObjectManager($objectManager);
        }
	}
}
