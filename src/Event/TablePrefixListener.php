<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Event;

use Zend\EventManager\AbstractListenerAggregate,
    Zend\EventManager\EventManagerInterface,
    Zend\Mvc\MvcEvent,
    Doctrine\Common\EventArgs,
    Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Events,
    Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
class TablePrefixListener extends AbstractListenerAggregate implements EventSubscriber
{
    /**
     * @var string
     */
    protected $prefix = 'cms_';

    /**
     * __construct
     *
     * @param string $prefix
     */
    public function __construct($prefix = null)
    {
        if (null !== $prefix) {
            $this->prefix = (string) $prefix;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_BOOTSTRAP, [$this, 'onBootstrap'], PHP_INT_MAX);
    }

    /**
     * Event callback to be triggered on bootstrap
     *
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $services = $e->getApplication()->getServiceManager();
        /* @var $om \Doctrine\ORM\EntityManager */
        $om = $services->get('Doctrine\\ORM\\EntityManager');
        $om->getEventManager()->addEventSubscriber($this);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata];
    }

    /**
     * @param EventArgs $eventArgs
     */
    public function loadClassMetadata(EventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $classMetadata->setTableName($this->prefix . $classMetadata->getTableName());
        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] == ClassMetadataInfo::MANY_TO_MANY
                && isset($classMetadata->associationMappings[$fieldName]['joinTable']['name'])
            ) {
                $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
            }
        }
    }
}
