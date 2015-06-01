<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Persistence;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Proxy\Proxy;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Persisters\Collection\OneToManyPersister;

/**
 * Persister for to-many collections.
 *
 * @author  Dmitry Popov <d.popov@altgraphic.com>
 */
class ToManyPersister extends OneToManyPersister
{
    /**
     * {@inheritdoc}
     */
    public function get(PersistentCollection $collection, $index)
    {
        $mapping = $collection->getMapping();

        if (!isset($mapping['indexBy'])) {
            throw new \BadMethodCallException('Selecting a collection by index is only supported on indexed collections.');
        }

        $persister = $this->uow->getEntityPersister($mapping['targetEntity']);

        return $persister->load(
            [$mapping['indexBy'] => $index],
            null,
            $mapping,
            [],
            null,
            1
        );
    }

    /**
     * {@inheritdoc}
     */
    public function count(PersistentCollection $collection)
    {
        $mapping   = $collection->getMapping();
        $persister = $this->uow->getEntityPersister($mapping['targetEntity']);

        return $persister->count();
    }

    /**
     * {@inheritdoc}
     */
    public function slice(PersistentCollection $collection, $offset, $length = null)
    {
        throw new \BadMethodCallException('Slicing a collection is not supported by this CollectionPersister.');
    }

    /**
     * {@inheritdoc}
     */
    public function containsKey(PersistentCollection $collection, $key)
    {
        $mapping = $collection->getMapping();

        if (!isset($mapping['indexBy'])) {
            throw new \BadMethodCallException('Selecting a collection by index is only supported on indexed collections.');
        }

        $persister = $this->uow->getEntityPersister($mapping['targetEntity']);
        $criteria = new Criteria(Criteria::expr()->eq($mapping['indexBy'], $key));

        return (bool) $persister->count($criteria);
    }

     /**
     * {@inheritdoc}
     */
    public function contains(PersistentCollection $collection, $element)
    {
        if (!$this->isValidEntityState($element)) {
            return false;
        }

        $mapping   = $collection->getMapping();
        $persister = $this->uow->getEntityPersister($mapping['targetEntity']);

        return $persister->exists($element);
    }

    /**
     * {@inheritdoc}
     */
    public function removeElement(PersistentCollection $collection, $element)
    {
        // This can never happen. To many can only be read-only.
        return;
    }
}
