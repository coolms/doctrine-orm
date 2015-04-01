<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/CmsDoctrineORM for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Hierarchy\Repository;

use Doctrine\ORM\AbstractQuery,
    Gedmo\Tree\Entity\Repository\NestedTreeRepository as GedmoNestedTreeRepository,
    CmsCommon\Persistence\HierarchyMapperInterface,
    CmsDoctrineORM\Mapping\Common\Repository\EntityRepositoryTrait;

/**
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
class NestedTreeRepository extends GedmoNestedTreeRepository implements HierarchyMapperInterface
{
    use EntityRepositoryTrait;

    /**
     * {@inheritDoc}
     */
    public function childrenQueryBuilder($node = null, $direct = false, $orderBy = null,
            $direction = 'ASC', $includeNode = false, array $options = [])
    {
        return parent::childrenQueryBuilder($node, $direct, $orderBy, $direction, $includeNode);
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
        $meta   = $this->getClassMetadata();
        $config = $this->listener->getConfiguration($this->getEntityManager(), $meta->getName());

        return $this->childrenQueryBuilder(
            $node,
            $direct,
            isset($config['root']) ? [$config['root'], $config['left']] : $config['left'],
            'ASC',
            $includeNode,
            $options
        );
    }
}
