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

use Zend\Form\Annotation as Form,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * Nested tree hierarchy representation
 * 
 * @Gedmo\Tree(type="nested")
 * @ORM\MappedSuperclass(repositoryClass="CmsDoctrineORM\Mapping\Hierarchy\Repository\NestedTreeRepository")
 */
abstract class AbstractNestedTree extends AbstractHierarchy
{
    /**
     * @var int
     * 
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft",type="integer")
     * @Form\Exclude()
     */
    protected $lft;

    /**
     * @var int
     * 
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt",type="integer")
     * @Form\Exclude()
     */
    protected $rgt;

    /**
     * @var int
     * 
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl",type="integer")
     * @Form\Exclude()
     */
    protected $lvl;

    /**
     * @param number $lft
     */
    public function setLft($lft)
    {
    	$this->lft = $lft;
    }

    /**
     * @return number
     */
    public function getLft()
    {
    	return $this->lft;
    }

    /**
     * @param number $rgt
     */
    public function setRgt($rgt)
    {
    	$this->rgt = $rgt;
    }

    /**
     * @return number
     */
    public function getRgt()
    {
    	return $this->rgt;
    }

    /**
     * @param number $lvl
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;
    }

    /**
     * @return number
     */
    public function getLvl()
    {
        return $this->lvl;
    }
}
