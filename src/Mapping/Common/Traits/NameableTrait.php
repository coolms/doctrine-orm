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
 * Trait for the entity to have a name
 *
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait NameableTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string",length=255,nullable=false)
     * @ORM\Translatable
     * @Form\Type("Text")
     * @Form\Filter({"name":"StripTags"})
     * @Form\Filter({"name":"StringTrim"})
     * @Form\Required(true)
     * @Form\Validator({
     *      "name":"StringLength",
     *      "options":{
     *          "encoding":"UTF-8",
     *          "min":0,
     *          "max":255,
     *          "break_chain_on_failure":true
     *      }})
     * @Form\Attributes({"required":true})
     * @Form\Options({
     *      "label":"Name",
     *      "text_domain":"default"})
     * @Form\Flags({"priority":900})
     */
    protected $name;

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
