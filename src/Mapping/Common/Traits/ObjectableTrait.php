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

trait ObjectableTrait
{
    /**
     * @var object
     *
     * @Form\Type("ObjectSelect")
     * @Form\Required(true)
     * @Form\Options({
     *      "empty_option":"Select object",
     *      "label":"Select object",
     *      "target_class":"CmsDoctrineORM\Entity\AbstractEntity",
     *      "text_domain":"default",
     *      })
     */
    protected $object;

    /**
     * @param object $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }
}
