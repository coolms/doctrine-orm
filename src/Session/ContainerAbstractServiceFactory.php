<?php 
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Session;

use Zend\ServiceManager\AbstractPluginManager,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\Session\Service\ContainerAbstractServiceFactory as SessionContainerAbstractServiceFactory,
    Doctrine\ORM\EntityManager,
    CmsDoctrine\Session\Container;

class ContainerAbstractServiceFactory extends SessionContainerAbstractServiceFactory
{
    /**
     * {@inheritDoc}
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $containers, $name, $requestedName)
    {
        if (!$containers instanceof AbstractPluginManager) {
            throw new \BadMethodCallException(
                'Domain session container abstract factory is meant to be used only with a plugin manager'
            );
        }

        $services = $containers->getServiceLocator();

        return !$this->getObjectManager($services)->getMetadataFactory()->isTransient($requestedName);
    }

    /**
     * {@inheritDoc}
     *
     * @return Container
     */
    public function createServiceWithName(ServiceLocatorInterface $containers, $name, $requestedName)
    {
        if (!$this->canCreateServiceWithName($containers, $name, $requestedName)) {
            throw new \BadMethodCallException(sprintf(
                'Domain session container abstract factory can\'t create container for "%s"',
                $requestedName
            ));
        }

        $services = $containers->getServiceLocator();

        return new Container(
            $requestedName,
            $this->getSessionManager($services),
            $this->getObjectManager($services)
        );
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
