<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Tool;

use Doctrine\Common\EventArgs,
    Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Events,
    Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;

/**
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
class TablePrefixSubscriber implements EventSubscriber
{
    /**
     * @var string
     */
    protected $prefix;

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
    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata];
    }

    /**
     * @param EventArgs $eventArgs
     */
    public function loadClassMetadata(EventArgs $eventArgs)
    {
        /* @var $classMetadata ClassMetadata */
        $classMetadata = $eventArgs->getClassMetadata();

        // If the entity is a subclass with STI, it gets its already prefixed table from inherited class
        if (!$classMetadata->isInheritanceTypeSingleTable() ||
            $classMetadata->getName() === $classMetadata->rootEntityName
        ) {
            $classMetadata->setTableName($this->prefix . $classMetadata->getTableName());
        }

        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] === ClassMetadataInfo::MANY_TO_MANY &&
                !$classMetadata->isInheritedAssociation($fieldName) &&
                isset($classMetadata->associationMappings[$fieldName]['joinTable']['name'])
            ) {
                $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
            }
        }
    }
}
