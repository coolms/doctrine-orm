<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Blameable\Traits;

trait CreatableTrait
{
    /**
     * @var mixed
     *
     * @ORM\Blameable(on="create")
     * @Form\Exclude()
     */
    protected $createdBy;

    /**
     * Sets createdBy
     *
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Retrieves createdBy
     *
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
