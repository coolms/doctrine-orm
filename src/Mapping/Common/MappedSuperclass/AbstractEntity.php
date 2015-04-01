<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Common\MappedSuperclass;

use Doctrine\ORM\Mapping as ORM,
    CmsDoctrineORM\Mapping\Common\EntityInterface;

/**
 * Abstract entity
 *
 * @ORM\MappedSuperclass(repositoryClass="CmsDoctrineORM\Mapping\Common\Repository\EntityRepository")
 */
abstract class AbstractEntity implements EntityInterface
{
    
}
