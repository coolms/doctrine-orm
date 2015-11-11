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
    Gedmo\Tree\Entity\Repository\NestedTreeRepository as BaseNestedTreeRepository,
    CmsCommon\Persistence\HierarchyMapperInterface,
    CmsDoctrineORM\Persistence\HierarchyMapperTrait;

/**
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
class NestedTreeRepository extends BaseNestedTreeRepository implements HierarchyMapperInterface
{
    use HierarchyRepositoryTrait,
        HierarchyMapperTrait;

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
