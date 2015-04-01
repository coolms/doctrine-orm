<?php 
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/CmsDoctrineORM for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Persistence;

use Zend\ServiceManager\AbstractFactoryInterface,
    Zend\ServiceManager\AbstractPluginManager,
    Zend\ServiceManager\ServiceLocatorInterface;

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
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getObjectManager(ServiceLocatorInterface $services)
    {
        return $services->get('Doctrine\\ORM\\EntityManager');
    }
}
