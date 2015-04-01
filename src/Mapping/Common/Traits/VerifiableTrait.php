<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 * 
 * @link      http://github.com/coolms/CmsDoctrineORM for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
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
     *      "translator_text_domain":"default",
     *      "checked_value":1,
     *      "unchecked_value":0,
     *      })
     */
    protected $verified = false;

    /**
     * @param bool $verified
     */
    public function setVerified($verified)
    {
        $this->verified = (bool) $verified;
    }

    /**
     * @return bool
     */
    public function getVerified()
    {
        return $this->verified;
    }
}
