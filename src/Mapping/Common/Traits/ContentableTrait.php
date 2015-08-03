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

trait ContentableTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="text",length=4294967295,nullable=true)
     * @ORM\Translatable
     * @Form\Type("Textarea")
     * @Form\Filter({"name":"StringTrim"})
     * @Form\Filter({"name":"Null"})
     * @Form\Required(false)
     * @Form\Validator({
     *      "name":"StringLength",
     *      "options":{
     *          "encoding":"UTF-8",
     *          "min":0,
     *          "max":4294967295,
     *          "break_chain_on_failure":true,
     *      }})
     * @Form\Attributes({"required":false})
     * @Form\Options({
     *      "label":"Content",
     *      "text_domain":"default"})
     * @Form\Flags({"priority":700})
     */
    protected $content;

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
