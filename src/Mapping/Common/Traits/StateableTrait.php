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
 * Trait for the entity to be stateable
 * 
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait StateableTrait
{
    /**
     * @var int
     * 
     * @ORM\Column(type="smallint",nullable=false)
     * @Form\Type("Select")
     * @Form\Filter({"name":"Null"})
     * @Form\Required(true)
     * @Form\Options({
     *      "empty_option":"Select state",
     *      "label":"State",
     *      "translator_text_domain":"default",
     *      })
     */
    protected $state;

    /**
     * @param int $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }
}
