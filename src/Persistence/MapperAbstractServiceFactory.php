<?php 
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Persistence;

use Zend\ServiceManager\AbstractFactoryInterface,
    Zend\ServiceManager\AbstractPluginManager,
    Zend\ServiceManager\ServiceLocatorInterface,
    Doctrine\ORM\EntityManager;

class MapperAbstractServiceFactory implements AbstractFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $mappers, $name, $requestedName)
    {
        if (!$mappers instanceof AbstractPluginManager) {
            throw new \BadMethodCallException('Mapper abstract factory is meant to be used only with a plugin manager');
        }

        $services = $mappers->getServiceLocator();

        return !$this->getObjectManager($services)->getMetadataFactory()->isTransient($requestedName);
    }

    /**
     * {@inheritDoc}
     */
    public function createServiceWithName(ServiceLocatorInterface $mappers, $name, $requestedName)
    {
        if (!$this->canCreateServiceWithName($mappers, $name, $requestedName)) {
            throw new \BadMethodCallException('Mapper abstract factory can\'t create mapper for "' . $requestedName . '"');
        }

        $services = $mappers->getServiceLocator();

        return $this->getObjectManager($services)->getRepository($requestedName);
    }

    /**
     * @param ServiceLocatorInterface $services
     * @return EntityManager
     */
    protected function getObjectManager(ServiceLocatorInterface $services)
    {
        return $services->get(EntityManager::class);
    }
}
