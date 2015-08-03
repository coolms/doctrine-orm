<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Hierarchy\Traits;

/**
 * Interface for the entity to be a part of the hierarchy with multiple roots
 *
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait RootableTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string",length=255,nullable=false)
     * @ORM\TreeRoot
     * @Form\Type("Text")
     * @Form\Filter({"name":"StripTags"})
     * @Form\Filter({"name":"StringTrim"})
     * @Form\Required(true)
     * @Form\AllowEmpty(true)
     * @Form\Validator({
     *      "name":"StringLength",
     *      "options":{
     *          "encoding":"UTF-8",
     *          "min":0,
     *          "max":255,
     *          "break_chain_on_failure":true,
     *      }})
     * @Form\Attributes({"required":true})
     * @Form\Options({
     *      "label":"Hierarchy root",
     *      "text_domain":"default"})
     */
    protected $root;

    /**
     * @param string $root
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
    }
}
