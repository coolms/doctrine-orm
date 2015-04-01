<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Metadatable\MappedSuperclass;

use Zend\Form\Annotation as Form,
    Doctrine\ORM\Mapping as ORM,
    CmsDoctrine\Mapping\Metadatable\MetadataInterface,
    CmsDoctrineORM\Mapping\Common\MappedSuperclass\AbstractIdentifiableEntity,
    CmsDoctrineORM\Mapping\Common\Traits\ObjectableTrait;

/**
 * Abstract metadata class
 *
 * @ORM\MappedSuperclass
 * @Form\Hydrator("DoctrineModule\Stdlib\Hydrator\DoctrineObject")
 */
abstract class AbstractMetadata extends AbstractIdentifiableEntity implements MetadataInterface
{
    use ObjectableTrait;
}
