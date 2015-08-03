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

trait ChangeableTrait
{
    /**
     * @var mixed
     *
     * @ORM\Blameable(on="change",field={})
     * @Form\Exclude()
     */
    protected $changedBy;

    /**
     * Sets changedBy
     *
     * @param mixed $changedBy
     */
    public function setChangedBy($changedBy)
    {
        $this->changedBy = $changedBy;
    }

    /**
     * Retrieves changedBy
     *
     * @return mixed
     */
    public function getChangedBy()
    {
        return $this->changedBy;
    }
}
