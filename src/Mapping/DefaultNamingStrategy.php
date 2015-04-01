<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
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
        if ($propertyName) {
            return $this->classToTableName($sourceEntity) . '_' . $this->propertyToColumnName($propertyName, $targetEntity);
        }
        return $this->classToTableName($sourceEntity) . '_' . $this->classToTableName($targetEntity);
    }
}
