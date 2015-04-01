<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/CmsDoctrineORM for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Dateable\MappedSuperclass;

use Zend\Form\Annotation as Form,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo,
    CmsBase\Mapping\Dateable\TimestampableInterface,
    CmsDoctrineORM\Mapping\Common\MappedSuperclass\AbstractIdentifiableEntity,
    CmsDoctrineORM\Mapping\Dateable\Traits\TimestampableTrait;

/**
 * Abstract timestampable entity
 *
 * @ORM\MappedSuperclass
 * @Form\Hydrator("DoctrineModule\Stdlib\Hydrator\DoctrineObject")
 */
abstract class AbstractTimestampableEntity extends AbstractIdentifiableEntity implements TimestampableInterface
{
    use TimestampableTrait;
}
