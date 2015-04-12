<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Common\Repository;

use Zend\Paginator\Paginator,
    Doctrine\ORM\AbstractQuery,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Doctrine\ORM\QueryBuilder,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    CmsDoctrineORM\Query\ExpressionBuilderTrait;

trait EntityRepositoryTrait
{
    use ExpressionBuilderTrait;

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    abstract public function getEntityManager();

    /**
     * @return \Doctrine\ORM\Mapping\ClassMetadata
     */
    abstract public function getClassMetadata();

    /**
     * @param string $alias
     * @param string $indexBy The index for the from.
     * @return QueryBuilder
     */
    abstract public function createQueryBuilder($alias, $indexBy = null);

    /**
     * @return string
     */
    abstract public function getClassName();

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return Paginator
     */
    public function getPaginator(array $criteria = [], array $orderBy = null)
    {
        if (func_num_args() > 0) {
            $query = $this->findByQuery($criteria, $orderBy);
        } else {
            $query = $this->findAllQuery();
        }

        $adapter = new DoctrineAdapter(new ORMPaginator($query));

        return new Paginator($adapter);
    }

    /**
     * @param mixed $id
     * @return QueryBuilder A Doctrine query builder instance
     */
    public function findQueryBuilder($id)
    {
        $keys   = $this->getClassMetadata()->getIdentifierFieldNames();
        $values = (array) $id;
        return $this->findByQueryBuilder(array_combine($keys, $values));
    }

    /**
     * @param mixed $id
     * @return QueryBuilder A Doctrine query builder instance
     */
    public function getFindQueryBuilder($id)
    {
        return $this->findQueryBuilder($id);
    }

    /**
     * @param mixed $id
     * @return AbstractQuery
     */
    public function findQuery($id)
    {
        return $this->findQueryBuilder($id)->getQuery();
    }

    /**
     * @param mixed $id
     * @return AbstractQuery        
     */
    public function getFindQuery($id)
    {
        return $this->findQuery($id);
    }

    /**
     * @param mixed         $id
     * @param string|null   $locale         A locale name
     * @param int           $hydrationMode  A Doctrine results hydration mode
     * @return mixed
     */
    public function find($id, $hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        return $this->findQuery($id)->getOneOrNullResult($hydrationMode);
    }

    /**
     * @param array         $criteria   
     * @param array|null    $orderBy    
     * @return QueryBuilder             A Doctrine query builder instance
     */
    public function findOneByQueryBuilder(array $criteria, array $orderBy = null)
    {
        return $this->findByQueryBuilder($criteria, $orderBy);
    }

    /**
     * @param array         $criteria   
     * @param array|null    $orderBy    
     * @return QueryBuilder             A Doctrine query builder instance
     */
    public function getFindOneByQueryBuilder(array $criteria, array $orderBy = null)
    {
        return $this->findOneByQueryBuilder($criteria, $orderBy);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return AbstractQuery    A Doctrine query instance
     */
    public function findOneByQuery(array $criteria, array $orderBy = null)
    {
        return $this->getFindOneByQueryBuilder($criteria, $orderBy)->getQuery();
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return AbstractQuery    A Doctrine query instance
     */
    public function getFindOneByQuery(array $criteria, array $orderBy = null)
    {
        return $this->findOneByQuery($criteria, $orderBy);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int $hydrationMode
     * @return mixed
     */
    public function findOneBy(array $criteria, array $orderBy = null,
            $hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        return $this->findOneByQuery($criteria, $orderBy)->getOneOrNullResult($hydrationMode);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int   $limit
     * @param int   $offset
     * @return QueryBuilder     A Doctrine query builder instance
     */
    public function findByQueryBuilder(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this->createQueryBuilder('entity');
        
        $expr = $this->buildExpr($qb, $qb->expr()->andX(), $criteria);
        if ($expr->count()) {
            $qb->where($expr);
        }
        
        if (null !== $orderBy) {
            $expr = $this->buildOrderByExpr($qb, null, array_keys($orderBy), array_values($orderBy));
            if ($expr->count()) {
                $qb->orderBy($expr);
            }
        }
        if (null !== $limit) {
            $qb->setMaxResults($limit);
        }
        if (null !== $offset) {
            $qb->setFirstResult($offset);
        }
        
        return $qb;
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int   $limit
     * @param int   $offset
     * @return QueryBuilder     A Doctrine query builder instance
     */
    public function getFindByQueryBuilder(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->findByQueryBuilder($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int   $limit
     * @param int   $offset
     * @return AbstractQuery    A Doctrine query instance
     */
    public function findByQuery(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->findByQueryBuilder($criteria, $orderBy, $limit, $offset)->getQuery();
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int   $limit
     * @param int   $offset
     * @return AbstractQuery    A Doctrine query instance
     */
    public function getFindByQuery(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->findByQuery($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria       
     * @param array $orderBy        
     * @param int $limit
     * @param int $offset           
     * @param int $hydrationMode    A Doctrine results hydration mode
     * @return mixed
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null,
            $offset = null, $hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        return $this->findByQuery($criteria, $orderBy, $limit, $offset)->getResult($hydrationMode);
    }

    /**
     * @return QueryBuilder A Doctrine query builder instance
     */
    public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('entity');
    }

    /**
     * @return QueryBuilder A Doctrine query builder instance
     */
    public function getFindAllQueryBuilder()
    {
        return $this->findAllQueryBuilder();
    }

    /**
     * @param string $locale    A locale name
     * @return AbstractQuery    A Doctrine query instance
     */
    public function findAllQuery()
    {
        return $this->getFindAllQueryBuilder()->getQuery();
    }

    /**
     * @return AbstractQuery    A Doctrine query instance
     */
    public function getFindAllQuery()
    {
        return $this->findAllQuery();
    }

    /**
     * @param int $hydrationMode    A Doctrine results hydration mode
     * @return mixed
     */
    public function findAll($hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        return $this->findAllQuery()->getResult($hydrationMode);
    }

    /**
     * Returns one (or null if not found) result
     *
     * @param QueryBuilder $qb      A Doctrine query builder instance
     * @param int $hydrationMode    A Doctrine results hydration mode
     * @return mixed
     */
    public function getOneOrNullResult(QueryBuilder $qb, $hydrationMode = null)
    {
        return $qb->getQuery()->getOneOrNullResult($hydrationMode);
    }

    /**
     * Returns results
     *
     * @param QueryBuilder $qb      A Doctrine query builder instance
     * @param int $hydrationMode    A Doctrine results hydration mode
     * @return mixed
     */
    public function getResult(QueryBuilder $qb, $hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        return $qb->getQuery()->getResult($hydrationMode);
    }

    /**
     * Returns array results
     *
     * @param QueryBuilder $qb  A Doctrine query builder instance
     * @return array
     */
    public function getArrayResult(QueryBuilder $qb)
    {
        return $qb->getQuery()->getArrayResult();
    }

    /**
     * Returns single result
     *
     * @param QueryBuilder $qb      A Doctrine query builder instance
     * @param int $hydrationMode    A Doctrine results hydration mode
     * @return mixed
     */
    public function getSingleResult(QueryBuilder $qb, $hydrationMode = null)
    {
        return $qb->getQuery()->getSingleResult($hydrationMode);
    }

    /**
     * Returns scalar result
     *
     * @param QueryBuilder $qb  A Doctrine query builder instance
     * @return mixed
     */
    public function getScalarResult(QueryBuilder $qb)
    {
        return $qb->getQuery()->getScalarResult();
    }

    /**
     * Returns single scalar result
     *
     * @param QueryBuilder $qb  A Doctrine query builder instance
     * @return mixed
     */
    public function getSingleScalarResult(QueryBuilder $qb, $locale = null)
    {
        return $qb->getQuery()->getSingleScalarResult();
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
        if (null !== $entity) {
            $this->guardEntity($entity);
        }
        $em = $this->getEntityManager();
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
        return $this->getEntityManager()->getHydratorFactory()->hydrate($entity, $data);
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
