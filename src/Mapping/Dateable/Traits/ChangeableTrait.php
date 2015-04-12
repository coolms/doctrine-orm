<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Dateable\Traits;

/**
 * Interface for the entity that might change
 * 
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait ChangeableTrait
{
    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime",nullable=true)
     * @Gedmo\Timestampable(on="change")
     * @Form\Type("StaticElement")
     * @Form\Options({
     *      "label":"Changed at",
     *      "translator_text_domain":"default",
     *      })
     * @Form\Flags({"priority":-870})
     */
    protected $changedAt;

    /**
     * @param \DateTime $changedAt
     */
    public function setChangedAt(\DateTime $changedAt)
    {
        $this->changedAt = $changedAt;
    }

    /**
     * @return \DateTime
     */
    public function getChangedAt()
    {
        return $this->changedAt;
    }
}
