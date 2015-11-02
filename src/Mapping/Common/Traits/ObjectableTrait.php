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

use CmsDoctrineORM\Mapping\Common\EntityInterface;

/**
 * Trait implementing Decorator pattern
 *
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait ObjectableTrait
{
    /**
     * @var EntityInterface
     *
     * @Form\Type("ObjectSelect")
     * @Form\Required(true)
     * @Form\Options({
     *      "empty_option":"Select object",
     *      "label":"Select object",
     *      "text_domain":"default"})
     * @Form\Flags({"priority":750})
     */
    protected $object;

    /**
     * @param object $object
     * @return self
     */
    public function setObject($object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * @return EntityInterface
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Proxy method
     *
     * @param string $method
     * @param array $params_arr
     * @return mixed
     */
    public function __call($method, $params_arr)
    {
        return call_user_func_array([$this->getObject(), $method], $param_arr);
    }
}
