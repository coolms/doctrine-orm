<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Dateable\Traits;

use DateTime;

trait UpdatableTrait
{
    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime",nullable=true)
     * @ORM\Timestampable(on="update")
     * @Form\Type("StaticElement")
     * @Form\Options({
     *      "label":"Updated at",
     *      "text_domain":"default"})
     * @Form\Flags({"priority":-880})
     */
    protected $updatedAt;

    /**
     * Set updated at
     *
     * @param DateTime $updatedAt
     * @return self
     */
    public function setUpdatedAt(DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get updated at
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
