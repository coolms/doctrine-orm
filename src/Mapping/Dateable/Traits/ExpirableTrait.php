<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Dateable\Traits;

trait ExpirableTrait
{
    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime",nullable=true)
     * @Form\Type("DateTime")
     * @Form\Attributes({
     *      "type":"datetime",
     *      "min":"2014-01-01T00:00:00Z",
     *      "max":"2024-01-01T00:00:00Z",
     *      "step":"1",
     *      })
     * @Form\Options({
     *      "format":"Y-m-d",
     *      "label":"Expire at",
     *      "translator_text_domain":"default",
     *      })
     */
    protected $expireAt;

    /**
     * Set expire at
     * 
     * @param \DateTime $expireAt
     */
    public function setExpireAt(\DateTime $expireAt = null)
    {
        $this->expireAt = $expireAt;
    }

    /**
     * Get expire at
     * 
     * @return \DateTime
     */
    public function getExpireAt()
    {
        return $this->expireAt;
    }
}
