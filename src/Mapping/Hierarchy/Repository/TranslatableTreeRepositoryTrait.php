<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Hierarchy\Repository;

use CmsDoctrineORM\Mapping\Translatable\Repository\TranslatableRepositoryTrait;

trait TranslatableTreeRepositoryTrait
{
    use TranslatableRepositoryTrait;

    /**
     * {@inheritDoc}
     */
    public function childrenQuery($node = null, $direct = false, $orderBy = null, $direction = 'ASC',
            $includeNode = false, array $options = [])
    {
        $qb = $this->childrenQueryBuilder($node, $direct, $orderBy, $direction, $includeNode, $options);
        return $this->getQuery($qb, empty($options['locale']) ? null : $options['locale']);
    }

    /**
     * {@inheritDoc}
     */
    public function getNodesHierarchyQuery($node = null, $direct = false,
            array $options = [], $includeNode = false)
    {
        $qb = $this->getNodesHierarchyQueryBuilder($node, $direct, $options, $includeNode);
        return $this->getQuery($qb, empty($options['locale']) ? null : $options['locale']);
    }
}
