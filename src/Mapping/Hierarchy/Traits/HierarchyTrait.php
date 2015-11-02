<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Hierarchy\Traits;

use Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\Collections\Collection,
    CmsCommon\Mapping\Hierarchy\HierarchyInterface;

/**
 * Trait for the entity/document to be a part of the hierarchy
 *
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait HierarchyTrait
{
    /**
     * @var int
     *
     * @ORM\Column(name="lvl",type="smallint",nullable=true)
     * @ORM\TreeLevel
     * @Form\Exclude()
     */
    protected $level;

    /**
     * @var HierarchyInterface
     *
     * @ORM\TreeParent
     */
    protected $parent;

    /**
     * @var HierarchyInterface[]
     */
    protected $children = [];

    /**
     * __construct
     *
     * Initializes children
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * @param number $level
     * @return self
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return number
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param HierarchyInterface $parent
     * @return self
     */
    public function setParent(HierarchyInterface $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return HierarchyInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param HierarchyInterface[] $children
     * @return self
     */
    public function setChildren($children)
    {
        $this->clearChildren();
        $this->addChildren($children);

        return $this;
    }

    /**
     * @param HierarchyInterface[] $children
     * @return self
     */
    public function addChildren($children)
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }

        return $this;
    }

    /**
     * @param HierarchyInterface $child
     * @return self
     */
    public function addChild(HierarchyInterface $child)
    {
        $this->getChildren()->add($child);
        return $this;
    }

    /**
     * @param HierarchyInterface[] $children
     * @return self
     */
    public function removeChildren($children)
    {
        foreach ($children as $child) {
            $this->removeChild($child);
        }

        return $this;
    }

    /**
     * @param HierarchyInterface $child
     * @return self
     */
    public function removeChild(HierarchyInterface $child)
    {
        $this->getChildren()->removeElement($child);
        return $this;
    }

    /**
     * Removes all children
     *
     * @return self
     */
    public function clearChildren()
    {
        $this->getChildren()->clear();
        return $this;
    }

    /**
     * @param HierarchyInterface $child
     * @return bool
     */
    public function hasChild(HierarchyInterface $child)
    {
        return $this->getChildren()->contains($child);
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return (bool) $this->getChildren()->count(); 
    }

    /**
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }
}
