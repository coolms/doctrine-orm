<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Common\Repository;

use Doctrine\ORM\EntityRepository as DoctrineEntityRepository,
    CmsCommon\Persistence\MapperInterface;

/**
 * @author  Dmitry Popov <d.popov@altgraphic.com>
 */
class EntityRepository extends DoctrineEntityRepository implements MapperInterface
{
    use EntityRepositoryTrait;
}
