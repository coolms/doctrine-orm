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
 * Trait for the entity to have a quantity
 *
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait QuantifiableTrait
{
    /**
     * @var int
     *
     * @ORM\Column(type="smallint",nullable=false)
     * @Form\Type("Number")
     * @Form\Filter({"name":"StringTrim"})
     * @Form\Filter({"name":"Int"})
     * @Form\Required(true)
     * @Form\Validator({"name":"Digits"})
     * @Form\Options({
     *      "label":"Quantity",
     *      "text_domain":"default"})
     * @Form\Flags({"priority":750})
     */
    protected $quantity;

    /**
     * @param int $quantity
     * @return self
     */
    public function setQuantity($quantity)
    {
        $this->quantity = (int) $quantity;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}
