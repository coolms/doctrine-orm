<?php 
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/CmsDoctrineORM for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Form\Annotation;

use Zend\ServiceManager\AbstractPluginManager,
    Zend\ServiceManager\ServiceLocatorInterface,
    Doctrine\ORM\EntityManager,
    CmsCommon\Form\Annotation\FormAbstractServiceFactory as BaseAnnotationFormAbstractServiceFactory;

class FormAbstractServiceFactory extends BaseAnnotationFormAbstractServiceFactory
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

        return !$this->getObjectManager($services)->getMetadataFactory()->isTransient($requestedName);
    }

    /**
     * {@inheritDoc}
     */
    protected function getAnnotationBuilder(ServiceLocatorInterface $services)
    {
        if (null === $this->annotationBuilder) {

            $om = $this->getObjectManager($services);
            $cacheStorage = $this->getAnnotationBuilderCache($services);
            $factory = $this->getFormFactory($services);

            $this->annotationBuilder = new AnnotationBuilder($om, $cacheStorage);
            $this->annotationBuilder->setFormFactory($factory);

            $em = $this->annotationBuilder->getEventManager();
            $em->attach(new ElementResolverListener($om));
        }

        return $this->annotationBuilder;
    }

    /**
     * @param ServiceLocatorInterface $services
     * @return EntityManager
     */
    protected function getObjectManager(ServiceLocatorInterface $services)
    {
        return $services->get('Doctrine\\ORM\\EntityManager');
    }
}
