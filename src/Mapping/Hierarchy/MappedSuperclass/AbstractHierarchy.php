<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/CmsDoctrineORM for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Hierarchy\MappedSuperclass;

use Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo,
    CmsBase\Mapping\Common\IdentifiableInterface,
    CmsBase\Mapping\Hierarchy\HierarchyInterface,
    CmsDoctrineORM\Mapping\Hierarchy\Traits\HierarchyTrait;

/**
 * Adjacency list hierarchy representation
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractHierarchy implements 
        IdentifiableInterface,
        HierarchyInterface
{
    use HierarchyTrait {
            HierarchyTrait::__construct as private __hierarchyConstruct;
        }

    /**
     * Initializes hierarchy
     */
    public function __construct()
    {
        $this->__hierarchyConstruct();
    }
}
