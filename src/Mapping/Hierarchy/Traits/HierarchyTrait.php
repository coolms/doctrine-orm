<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 * 
 * @link      http://github.com/coolms/CmsDoctrineORM for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Hierarchy\Traits;

use Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\Collections\Collection,
    CmsBase\Mapping\Hierarchy\HierarchyInterface;

/**
 * Trait for the entity to be a part of the hierarchy
 * 
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait HierarchyTrait
{
    /**
     * @var HierarchyInterface
     * 
     * @Gedmo\TreeParent
     */
    protected $parent;

    /**
     * @var HierarchyInterface[]
     */
    protected $children;

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
     * @param HierarchyInterface $parent
     */
    public function setParent(HierarchyInterface $parent = null)
    {
        $this->parent = $parent;
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
     */
    public function setChildren($children)
    {
        $this->clearChildren();
        $this->addChildren($children);
    }

    /**
     * @param HierarchyInterface[] $children
     */
    public function addChildren($children)
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    /**
     * @param HierarchyInterface $child
     */
    public function addChild(HierarchyInterface $child)
    {
        $this->getChildren()->add($child);
    }

    /**
     * @param HierarchyInterface[] $children
     */
    public function removeChildren($children)
    {
        foreach ($children as $child) {
            $this->removeChild($child);
        }
    }

    /**
     * @param HierarchyInterface $child
     */
    public function removeChild(HierarchyInterface $child)
    {
        $this->getChildren()->removeElement($child);
    }

    /**
     * Removes all children
     */
    public function clearChildren()
    {
        $this->getChildren()->clear();
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
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }
}
