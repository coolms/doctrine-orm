<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\ElementCollection;

use Doctrine\Common\EventArgs,
    Gedmo\Mapping\MappedEventSubscriber;

/**
 * The ElementCollection listener handles the ElementCollection annotation
 * removed in Doctrine 2.5.
 *
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
class ElementCollectionSubscriber extends MappedEventSubscriber
{
    /**
     * ElementCollection annotation class
     */
    const ELEMENT_COLLECTION_ANNOTATION = 'CmsDoctrineORM\\Mapping\\ElementCollection\\Annotation\\ElementCollection';

    /**
     * ElementCollection annotation alias
     */
    const ELEMENT_COLLECTION_ANNOTATION_ALIAS = 'Doctrine\\ORM\\Mapping\\ElementCollection';

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();

        if (!class_exists(static::ELEMENT_COLLECTION_ANNOTATION_ALIAS)) {
            class_alias(static::ELEMENT_COLLECTION_ANNOTATION,
                static::ELEMENT_COLLECTION_ANNOTATION_ALIAS);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return ['loadClassMetadata'];
    }

    /**
     * @param EventArgs $eventArgs
     */
    public function loadClassMetadata(EventArgs $eventArgs)
    {
        /* @var $ea \Gedmo\Mapping\Event\AdapterInterface */
        $ea = $this->getEventAdapter($eventArgs);
        /* @var $om \Doctrine\Common\Persistence\ObjectManager */
        $om = $ea->getObjectManager();
        /* @var $meta \Doctrine\Common\Persistence\Mapping\ClassMetadata */
        $meta = $eventArgs->getClassMetadata();

        $this->loadMetadataForObjectClass($om, $meta);
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespace()
    {
        return __NAMESPACE__;
    }
}
