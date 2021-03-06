<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Common\Traits;

/**
 * Trait for the entity to be verifiable
 *
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait VerifiableTrait
{
    /**
     * @var bool
     *
     * @ORM\Column(name="verified",type="boolean",nullable=false)
     * @Form\Type("Checkbox")
     * @Form\Filter({"name":"Boolean"})
     * @Form\Required(false)
     * @Form\Options({
     *      "label":"Verified",
     *      "checked_value":1,
     *      "unchecked_value":0,
     *      "text_domain":"default"})
     * @Form\Flags({"priority":500})
     */
    protected $verified = false;

    /**
     * @param bool $verified
     * @return self
     */
    public function setVerified($verified)
    {
        $this->verified = (bool) $verified;
        return $this;
    }

    /**
     * @return bool
     */
    public function getVerified()
    {
        return $this->verified;
    }
}
