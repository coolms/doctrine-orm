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

use Zend\EventManager\EventManagerAwareTrait,
    Zend\ServiceManager\ServiceLocatorAwareTrait,
    Zend\Stdlib\Hydrator\HydratorInterface,
    Zend\Paginator\Adapter\AdapterInterface,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Mapping\ClassMetadata,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    CmsDoctrine\Stdlib\Hydrator\DoctrineObject,
    CmsDoctrine\Tool\InitializerSubscriber;
use CmsDoctrineORM\Persistence\Filter\Filter;

trait MapperTrait
{
    use EventManagerAwareTrait,
        ServiceLocatorAwareTrait;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @return EntityManager
     */
    abstract public function getEntityManager();

    /**
     * @return string
     */
    abstract public function getClassName();

    /**
     * @return ClassMetadata
     */
    public function getClassMetadata()
    {
        return $this->getEntityManager()->getClassMetadata($this->getClassName());
    }

    /**
     * Since Doctrine closes the EntityManager after a Exception, we have to create
     * a fresh copy (so it is possible to save logs in the current request)
     */
    private function recoverEntityManager()
    {
        $this->_em = EntityManager::create(
            $this->getEntityManager()->getConnection(),
            $this->getEntityManager()->getConfiguration()
        );
    }

    /**
     * Retrieves paginator for records
     *
     * @param array $criteria
     * @param array $orderBy
     * @param int $currentPageNumber
     * @param int $itemCountPerPage
     * @return AdapterInterface
     */
    public function getPaginatorAdapter(
        array $criteria = [],
        array $orderBy = []
    ) {
        if ($criteria || $orderBy) {
            $query = $this->findByQuery($criteria, $orderBy);
        } else {
            $query = $this->findAllQuery();
        }

        $adapter = new DoctrineAdapter(new ORMPaginator($query));

        return $adapter;
    }

    /**
     * @param mixed         $id
     * @param string|null   $locale         A locale name
     * @param int           $hydrationMode  A Doctrine results hydration mode
     * @return mixed
     */
    abstract public function find($id);

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return mixed
     */
    abstract public function findOneBy(array $criteria, array $orderBy = null);

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    abstract public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    /**
     * @param int $hydrationMode    A Doctrine results hydration mode
     * @return mixed
     */
    abstract public function findAll();

    /**
     * @param array $criteria
     * @return object
     */
    public function findOneOrCreate(array $criteria = null)
    {
        if (!($entity = $this->findOneBy($criteria))) {
            $entity = $this->create($criteria);
        }

        return $entity;
    }

    /**
     * @param array $args
     * @return object
     */
    public function create(array $args = null)
    {
        $meta = $this->getClassMetadata();

        $identityFields = $meta->getIdentifierFieldNames();
        $identityFields = array_diff($identityFields, $args);

        foreach ($identityFields as $id) {
            $args[$id] = null;
        }

        $class = $this->getClassMetadata()->getReflectionClass();
        $construct = $class->getConstructor();

        if ($construct && $construct->getNumberOfRequiredParameters()) {
            $instanceArgs = [];
            foreach($construct->getParameters() as $parameter) {
                $name = $parameter->getName();
                if (isset($args[$name])) {
                    $instanceArgs[$name] = $args[$name];
                    unset($args[$name]);
                }
            }

            $instance = $class->newInstanceArgs($instanceArgs);
        } else {
            $className = $class->getName();
            $instance = new $className();
        }

        $em = $this->getEntityManager();
        foreach ($args as $name => $value) {            
            if ($class->hasProperty($name)) {
                if (!is_object($value) && $meta->isSingleValuedAssociation($name)) {
                    $entityName = $meta->getAssociationTargetClass($name);
                    $value = $em->find($entityName, $value);
                }

                $property = $class->getProperty($name);
                $property->setAccessible(true);
                $property->setValue($instance, $value);
            }
        }

        $listeners = $em->getEventManager()->getListeners('postLoad');
        foreach ($listeners as $listener) {
            if ($listener instanceof InitializerSubscriber) {
                $listener->initialize($instance);
            }
        }

        $em->getUnitOfWork()->computeChangeSet($meta, $instance);

        return $instance;
    }

    /**
     * @param object $entity
     * @return self
     */
    public function add($entity)
    {
        $this->guardEntity($entity);
        $em = $this->getEntityManager();
        $em->persist($entity);
        return $this;
    }

    /**
     * @param object $entity
     * @return self
     */
    public function update($entity)
    {
        $this->guardEntity($entity);
        $em = $this->getEntityManager();
        $em->persist($entity);
        return $this;
    }

    /**
     * @param object $entity
     * @return self
     */
    public function remove($entity)
    {
        $this->guardEntity($entity);
        $em = $this->getEntityManager();
        $em->remove($entity);
        return $this;
    }

    /**
     * @param object|null $entity
     * @return void
     */
    public function save($entity = null)
    {
        $em = $this->getEntityManager();
        if (null !== $entity) {
            $this->guardEntity($entity);
            $meta = $this->getClassMetadata();
            $mappings = $meta->getAssociationNames();
            $uow = $em->getUnitOfWork();
            $isChangeSetsComputed = false;
            foreach ($mappings as $name) {
                if ($meta->isAssociationInverseSide($name)) {
                    $prop = $meta->getReflectionProperty($name);
                    $prop->setAccessible(true);
                    if (!($assocValue = $prop->getValue($entity))) {
                        continue;
                    }

                    if ($meta->isSingleValuedAssociation($name)) {
                        $uow->recomputeSingleEntityChangeSet($em->getClassMetadata(get_class($assocValue)), $assocValue);
                        if ($em->getUnitOfWork()->isScheduledForUpdate($assocValue)) {
                            $em->flush($assocValue);
                        }
                    } elseif (!$isChangeSetsComputed) {
                        $uow->computeChangeSets();
                        $isChangeSetsComputed = true;
                    }
                }
            }
        }

        $em->flush($entity);
    }

    /**
     * Hydrate $entity with the provided $data.
     *
     * @param  array    $data
     * @param  object   $entity
     * @return object
     */
    public function hydrate(array $data, $entity)
    {
        $this->guardEntity($entity);
        return $this->getHydrator()->hydrate($data, $entity);
    }

    /**
     * @param object $entity
     * @return array
     */
    public function extract($entity)
    {
        $this->guardEntity($entity);
        return $this->getHydrator()->extract($entity);
    }

    /**
     * @return HydratorInterface
     */
    protected function getHydrator()
    {
        if (null === $this->hydrator) {
            $this->setHydrator(new DoctrineObject($this->getEntityManager()));
        }

        return $this->hydrator;
    }

    /**
     * @param HydratorInterface $hydrator
     * @return self
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }

    /**
     * @param object $entity
     * @throws \DomainException
     * @return void
     */
    private function guardEntity($entity)
    {
        $entityClass = $this->getClassName();
        if (!$entity instanceof $entityClass) {
            throw new \DomainException(sprintf(
                'Entity must be instance of %s; %s given',
                $entityClass,
                is_object($entity) ? get_class($entity) : gettype($entity)
            ));
        }
    }
}
