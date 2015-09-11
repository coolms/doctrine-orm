<?php 
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Form\Annotation;

use Zend\ServiceManager\AbstractPluginManager,
    Zend\ServiceManager\ServiceLocatorInterface,
    CmsCommon\Form\Annotation\FormAbstractServiceFactory as CommonFormAbstractServiceFactory;

class FormAbstractServiceFactory extends CommonFormAbstractServiceFactory
{
    /**
     * {@inheritDoc}
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $formElements, $name, $requestedName)
    {
        if (!$formElements instanceof AbstractPluginManager) {
            throw new \BadMethodCallException('This abstract factory is meant to be used only with a plugin manager');
        }

        $services = $formElements->getServiceLocator();
        $om = $this->getAnnotationBuilder($services)->getObjectManager();
        if (!class_exists($requestedName)) {
            $requestedName = $om->getClassMetadata($requestedName)->getName();
        }

        return !$om->getMetadataFactory()->isTransient($requestedName);
    }

    /**
     * {@inheritDoc}
     */
    public function createServiceWithName(ServiceLocatorInterface $formElements, $name, $requestedName)
    {
        $services = $formElements->getServiceLocator();
        $om = $this->getAnnotationBuilder($services)->getObjectManager();
        if (!class_exists($requestedName)) {
            $requestedName = $om->getClassMetadata($requestedName)->getName();
        }

        return parent::createServiceWithName($formElements, $name, $requestedName);
    }

    /**
     * @return AnnotationBuilder
     */
    protected function getAnnotationBuilder(ServiceLocatorInterface $services)
    {
        return $services->get('Doctrine\\ORM\\FormAnnotationBuilder');
    }
}
