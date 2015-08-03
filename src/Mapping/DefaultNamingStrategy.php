<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping;

use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;

/**
 * Naming strategy implementing the underscore naming convention.
 * Converts 'MyEntity' to 'my_entity' or 'MY_ENTITY'.
 *
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
class DefaultNamingStrategy extends UnderscoreNamingStrategy
{
    /**
     * {@inheritDoc}
     */
    public function joinTableName($sourceEntity, $targetEntity, $propertyName = null)
    {
        if (interface_exists($targetEntity) && strpos($targetEntity, 'Interface') !== false) {
            $targetEntity = str_replace('Interface', '', $targetEntity);
        }

        if ($propertyName) {
            return $this->classToTableName($sourceEntity) . '_' . $this->propertyToColumnName($propertyName, $targetEntity);
        }

        return $this->classToTableName($sourceEntity) . '_' . $this->classToTableName($targetEntity);
    }

    /**
     * {@inheritDoc}
     */
    public function joinKeyColumnName($entityName, $referencedColumnName = null)
    {
        if (interface_exists($entityName) && strpos($entityName, 'Interface') !== false) {
            $entityName = str_replace('Interface', '', $entityName);
        }

        return parent::joinKeyColumnName($entityName, $referencedColumnName);
    }
}
