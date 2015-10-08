<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Factory\Form;

use Zend\ServiceManager\ServiceLocatorInterface,
    Zend\ServiceManager\FactoryInterface,
    Doctrine\ORM\EntityManager,
    CmsDoctrine\Form\Element\ObjectRadio;

/**
 * Factory for {@see ObjectRadio}
 */
class ObjectRadioFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return ObjectRadio
     */
    public function createService(ServiceLocatorInterface $pluginManager)
    {
        $services      = $pluginManager->getServiceLocator();
        $entityManager = $services->get(EntityManager::class);
        $element       = new ObjectRadio;

        $element->getProxy()->setObjectManager($entityManager);

        return $element;
    }
}
