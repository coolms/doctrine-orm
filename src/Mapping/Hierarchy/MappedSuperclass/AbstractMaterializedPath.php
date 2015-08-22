<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Hierarchy\MappedSuperclass;

use Zend\Form\Annotation as Form,
    Doctrine\ORM\Mapping as ORM,
    CmsCommon\Mapping\Hierarchy\MaterializedPathInterface;

/**
 * Materialized path hierarchy representation
 *
 * @ORM\MappedSuperclass(repositoryClass="CmsDoctrineORM\Mapping\Hierarchy\Repository\MaterializedPathRepository")
 * @ORM\Tree(type="materializedPath")
 */
abstract class AbstractMaterializedPath extends AbstractHierarchy implements MaterializedPathInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="path",type="string",length=10000,nullable=true)
     * @ORM\TreePath(separator="/",appendId=false,startsWithSeparator=true,endsWithSeparator=false)
     * @Form\Exclude()
     */
    protected $path;

    /**
     * @param string $path
     */
    public function setPath($path)
    {
    	$this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
    	return $this->path;
    }
}
