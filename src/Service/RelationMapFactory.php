<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/CmsDoctrineORM for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Service;

use Zend\ServiceManager\ServiceLocatorInterface,
    DoctrineModule\Service\AbstractFactory,
    CmsDoctrine\Mapping\Relation\RelationSubscriber;

class RelationMapFactory extends AbstractFactory
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $options \CmsDoctrineORM\Options\RelationMap */
        $options      = $this->getOptions($serviceLocator, 'relation_map');
        $eventManager = $serviceLocator->get($options->getEventManager());

        foreach ($options->getMappings() as $objectType => $mapping) {
            $subscriber = new RelationSubscriber($objectType, $mapping);
            $eventManager->addEventSubscriber($subscriber);
        }

        return $eventManager;
    }

    /**
     * Get the class name of the options associated with this factory.
     *
     * @return string
     */
    public function getOptionsClass()
    {
        return 'CmsDoctrineORM\\Options\\RelationMap';
    }
}
