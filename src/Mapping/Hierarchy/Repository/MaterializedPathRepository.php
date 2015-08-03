<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Hierarchy\Repository;

use Doctrine\ORM\AbstractQuery,
    Gedmo\Tree\Entity\Repository\MaterializedPathRepository as GedmoMaterializedPathRepository,
    CmsCommon\Persistence\HierarchyMapperInterface,
    CmsDoctrineORM\Mapping\Common\Repository\EntityRepositoryTrait;

/**
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
class MaterializedPathRepository extends GedmoMaterializedPathRepository implements HierarchyMapperInterface
{
    use EntityRepositoryTrait;

    /**
     * {@inheritDoc}
     */
    public function childrenQueryBuilder($node = null, $direct = false, $orderBy = null,
            $direction = 'ASC', $includeNode = false, array $options = [])
    {
        return parent::getChildrenQueryBuilder($node, $direct, $orderBy, $direction, $includeNode);
    }

    /**
     * {@inheritDoc}
     */
    public function getChildrenQueryBuilder($node = null, $direct = false, $orderBy = null,
            $direction = 'ASC', $includeNode = false, array $options = [])
    {
        return $this->childrenQueryBuilder($node, $direct, $orderBy, $direction, $includeNode, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function childrenQuery($node = null, $direct = false, $orderBy = null, $direction = 'ASC',
            $includeNode = false, array $options = [])
    {
        return $this->childrenQueryBuilder($node, $direct, $orderBy, $direction, $includeNode, $options)
            ->getQuery();
    }

    /**
     * {@inheritDoc}
     */
    public function getChildrenQuery($node = null, $direct = false, $orderBy = null, $direction = 'ASC',
            $includeNode = false, array $options = [])
    {
        return $this->childrenQuery($node, $direct, $orderBy, $direction, $includeNode, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function children($node = null, $direct = false, $orderBy = null, $direction = 'ASC',
            $includeNode = false, array $options = [], $hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        return $this->childrenQuery($node, $direct, $orderBy, $direction, $includeNode, $options)
            ->getResult($hydrationMode);
    }

    /**
     * {@inheritDoc}
     */
    public function getChildren($node = null, $direct = false, $orderBy = null, $direction = 'ASC',
            $includeNode = false, array $options = [], $hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        return $this->children($node, $direct, $orderBy, $direction, $includeNode, $options, $hydrationMode);
    }

    /**
     * {@inheritDoc}
     */
    public function getNodesHierarchyQueryBuilder($node = null, $direct = false,
            array $options = [], $includeNode = false)
    {
        $sortBy = [
            'field' => null,
            'dir'   => 'asc',
        ];

        if (isset($options['childSort'])) {
            $sortBy = array_merge($sortBy, $options['childSort']);
        }

        return $this->getChildrenQueryBuilder($node, $direct, $sortBy['field'], $sortBy['dir'], $includeNode, $options);
    }
}
