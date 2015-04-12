<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Translatable\Repository;

use Doctrine\ORM\AbstractQuery,
    Doctrine\ORM\QueryBuilder,
    CmsDoctrineORM\Mapping\Common\Repository\EntityRepositoryTrait,
    CmsDoctrineORM\Query\TranslatableQueryProviderTrait;

trait TranslatableRepositoryTrait
{
    use EntityRepositoryTrait,
        TranslatableQueryProviderTrait;

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function findQuery($id, $locale = null)
    {
        $qb = $this->findQueryBuilder($id);
        return $this->getQuery($qb, $locale);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name      
     */
    public function getFindQuery($id, $locale = null)
    {
        return $this->findQuery($id, $locale);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function find($id, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $locale = null)
    {
        return $this->findQuery($id, $locale)->getOneOrNullResult($hydrationMode);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function findOneByQuery(array $criteria, array $orderBy = null, $locale = null)
    {
        $qb = $this->getFindOneByQueryBuilder($criteria, $orderBy);
        return $this->getQuery($qb, $locale);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function getFindOneByQuery(array $criteria, array $orderBy = null, $locale = null)
    {
        return $this->findOneByQuery($criteria, $orderBy, $locale);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function findOneBy(array $criteria, array $orderBy = null,
            $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $locale = null)
    {
        return $this->findOneByQuery($criteria, $orderBy, $locale)->getOneOrNullResult($hydrationMode);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function findByQuery(array $criteria, array $orderBy = null, $limit = null, $offset = null, $locale = null)
    {
        $qb = $this->findByQueryBuilder($criteria, $orderBy, $limit, $offset);
        return $this->getQuery($qb, $locale);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function getFindByQuery(array $criteria, array $orderBy = null, $limit = null, $offset = null, $locale = null)
    {
        return $this->findByQuery($criteria, $orderBy, $limit, $offset, $locale);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null,
            $offset = null, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $locale = null)
    {
        return $this->findByQuery($criteria, $orderBy, $limit, $offset, $locale)->getResult($hydrationMode);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function findAllQuery($locale = null)
    {
        $qb = $this->getFindAllQueryBuilder();
        return $this->getQuery($qb, $locale);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function getFindAllQuery($locale = null)
    {
        return $this->findAllQuery($locale);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function findAll($hydrationMode = AbstractQuery::HYDRATE_OBJECT, $locale = null)
    {
        return $this->findAllQuery($locale)->getResult($hydrationMode);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function getOneOrNullResult(QueryBuilder $qb, $hydrationMode = null, $locale = null)
    {
        return $this->getQuery($qb, $locale)->getOneOrNullResult($hydrationMode);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function getResult(QueryBuilder $qb, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $locale = null)
    {
        return $this->getQuery($qb, $locale)->getResult($hydrationMode);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function getArrayResult(QueryBuilder $qb, $locale = null)
    {
        return $this->getQuery($qb, $locale)->getArrayResult();
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function getSingleResult(QueryBuilder $qb, $hydrationMode = null, $locale = null)
    {
        return $this->getQuery($qb, $locale)->getSingleResult($hydrationMode);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function getScalarResult(QueryBuilder $qb, $locale = null)
    {
        return $this->getQuery($qb, $locale)->getScalarResult();
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function getSingleScalarResult(QueryBuilder $qb, $locale = null)
    {
        return $this->getQuery($qb, $locale)->getSingleScalarResult();
    }
}
