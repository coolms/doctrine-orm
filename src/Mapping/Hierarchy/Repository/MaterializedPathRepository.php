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
    CmsDoctrineORM\Persistence\HierarchyMapperTrait;

/**
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
class MaterializedPathRepository extends GedmoMaterializedPathRepository implements HierarchyMapperInterface
{
    use HierarchyRepositoryTrait,
        HierarchyMapperTrait;

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

        return $this->childrenQueryBuilder($node, $direct, $sortBy['field'], $sortBy['dir'], $includeNode, $options);
    }
}
