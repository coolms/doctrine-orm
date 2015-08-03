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
 * Trait for the entity to have an annotation
 *
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait AnnotatableTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string",length=10000,nullable=true)
     * @Form\Type("Textarea")
     * @Form\Filter({"name":"StringTrim"})
     * @Form\Filter({"name":"Null"})
     * @Form\Required(false)
     * @Form\Validator({
     *      "name":"StringLength",
     *      "options":{
     *          "encoding":"UTF-8",
     *          "min":0,
     *          "max":10000,
     *          "break_chain_on_failure":true,
     *      }})
     * @Form\Options({
     *      "label":"Annotation",
     *      "text_domain":"default"})
     * @Form\Flags({"priority":750})
     */
    protected $annotation;

    /**
     * @param string $annotation
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;
    }

    /**
     * @return string
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }
}
