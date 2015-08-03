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

trait UpdatableTrait
{
    /**
     * @var mixed
     *
     * @ORM\Blameable(on="update")
     * @Form\Exclude()
     */
    protected $updatedBy;

    /**
     * Sets updatedBy
     *
     * @param mixed $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * Retrieves updatedBy
     *
     * @return mixed
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
}
