<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Common\Traits;

/**
 * Trait for the entity to have an identity
 * 
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait IdentifiableTrait
{
    /**
     * @var int
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Form\Type("StaticElement")
     * @Form\Required(true)
     * @Form\AllowEmpty(true)
     * @Form\Options({
     *      "label":"Identifier",
     *      "translator_text_domain":"default",
     *      })
     * @Form\Flags({"priority":1000})
     */
    protected $id;

    /**
     * @param number $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return number
     */
    public function getId()
    {
        return $this->id;
    }
}
