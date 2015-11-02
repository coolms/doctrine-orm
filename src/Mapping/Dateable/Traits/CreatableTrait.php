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

trait CreatableTrait
{
    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime",nullable=false)
     * @ORM\Timestampable(on="create")
     * @Form\Type("StaticElement")
     * @Form\Options({
     *      "label":"Created at",
     *      "text_domain":"default"})
     * @Form\Flags({"priority":-890})
     */
    protected $createdAt;

    /**
     * Set created at
     *
     * @param DateTime $createdAt
     * @return self
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get created at
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
