<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Common\MappedSuperclass;

use Doctrine\ORM\Mapping as ORM,
    Zend\Form\Annotation as Form,
    CmsCommon\Mapping\Common\IdentifiableInterface,
    CmsDoctrineORM\Mapping\Common\Traits\IdentifiableTrait;

/**
 * Abstract identifiable entity
 *
 * @ORM\MappedSuperclass
 * @Form\Exclude()
 */
abstract class AbstractIdentifiableEntity extends AbstractEntity implements IdentifiableInterface
{
    use IdentifiableTrait;
}
