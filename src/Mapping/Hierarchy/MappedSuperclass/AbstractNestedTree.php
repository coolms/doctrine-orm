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
    Doctrine\ORM\Mapping as ORM;

/**
 * Nested tree hierarchy representation
 *
 * @ORM\MappedSuperclass(repositoryClass="CmsDoctrineORM\Mapping\Hierarchy\Repository\NestedTreeRepository")
 * @ORM\Tree(type="nested")
 */
abstract class AbstractNestedTree extends AbstractHierarchy
{
    /**
     * @var int
     *
     * @ORM\Column(name="lft",type="integer")
     * @ORM\TreeLeft
     * @Form\Exclude()
     */
    protected $left;

    /**
     * @var int
     *
     * @ORM\Column(name="rgt",type="integer")
     * @ORM\TreeRight
     * @Form\Exclude()
     */
    protected $right;

    /**
     * @var int
     *
     * @ORM\Column(name="lvl",type="integer")
     * @ORM\TreeLevel
     * @Form\Exclude()
     */
    protected $level;

    /**
     * @param number $left
     */
    public function setLeft($left)
    {
    	$this->left = $left;
    }

    /**
     * @return number
     */
    public function getLeft()
    {
    	return $this->left;
    }

    /**
     * @param number $right
     */
    public function setRight($right)
    {
    	$this->right = $right;
    }

    /**
     * @return number
     */
    public function getRight()
    {
    	return $this->right;
    }

    /**
     * @param number $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return number
     */
    public function getLevel()
    {
        return $this->level;
    }
}
