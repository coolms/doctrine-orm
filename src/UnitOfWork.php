<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM;

use Doctrine\ORM\UnitOfWork as DoctrineORMUnitOfWork;

use Doctrine\ORM\Mapping\ClassMetadata;

use Doctrine\ORM\Persisters\Collection\OneToManyPersister;
use Doctrine\ORM\Persisters\Collection\ManyToManyPersister;
use CmsDoctrineORM\Persistence\ToManyPersister;

/**
 * {@inheritDoc}
 *
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
class UnitOfWork extends DoctrineORMUnitOfWork
{
    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     * {@inheritDoc}
     */
    public function getCollectionPersister(array $association)
    {
        $role = isset($association['cache'])
            ? $association['sourceEntity'] . '::' . $association['fieldName']
            : $association['type'];

        $refl = $this->getReflectionClass();
        $parent = $refl->getParentClass();

        $collectionPersisters = $parent->getProperty('collectionPersisters');
        $collectionPersisters->setAccessible(true);

        if (isset($collectionPersisters->getValue($this)[$role])) {
            return $collectionPersisters->getValue($this)[$role];
        }

        $em = $parent->getProperty('em');
        $em->setAccessible(true);

        switch ($association['type']) {
            case ClassMetadata::ONE_TO_MANY:
                $persister = new OneToManyPersister($em->getValue($this));
                break;
            case ClassMetadata::MANY_TO_MANY:
                $persister = new ManyToManyPersister($em->getValue($this));
                break;
            default:
                $persister = new ToManyPersister($em->getValue($this));
        }

        $hasCache = $parent->getProperty('hasCache');
        $hasCache->setAccessible(true);

        if ($hasCache->getValue($this) && isset($association['cache'])) {
            $persister = $em->getValue($this)->getConfiguration()
                ->getSecondLevelCacheConfiguration()
                ->getCacheFactory()
                ->buildCachedCollectionPersister($this->em, $persister, $association);
        }

        $persisters = $collectionPersisters->getValue($this);
        $persisters[$role] = $persister;
        $collectionPersisters->setValue($this, $persisters);

        return $persisters[$role];
    }

    /**
     * @return ReflectionClass
     */
    protected function getReflectionClass()
    {
        if (null === $this->reflectionClass) {
            $this->reflectionClass = new \ReflectionClass(__CLASS__);
        }

        return $this->reflectionClass;
    }
}
