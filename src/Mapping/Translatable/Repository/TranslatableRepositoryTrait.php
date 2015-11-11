<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Translatable\Repository;

use Doctrine\ORM\AbstractQuery,
    Doctrine\ORM\Query,
    Doctrine\ORM\QueryBuilder,
    Gedmo\Translatable\Query\TreeWalker\TranslationWalker,
    CmsDoctrine\Mapping\Translatable\TranslatableSubscriber;

/**
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait TranslatableRepositoryTrait
{
    /**
     * @var string Locale
     */
    protected $translatableLocale;

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function findQuery($id, $locale = null)
    {
        $qb = $this->findQueryBuilder($id);
        return $this->getTranslatableQuery($qb, $locale);
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
        return $this->getTranslatableQuery($qb, $locale);
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
        return $this->getTranslatableQuery($qb, $locale);
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
        return $this->getTranslatableQuery($qb, $locale);
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
        return $this->getTranslatableQuery($qb, $locale)->getOneOrNullResult($hydrationMode);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function getResult(QueryBuilder $qb, $hydrationMode = AbstractQuery::HYDRATE_OBJECT, $locale = null)
    {
        return $this->getTranslatableQuery($qb, $locale)->getResult($hydrationMode);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function getArrayResult(QueryBuilder $qb, $locale = null)
    {
        return $this->getTranslatableQuery($qb, $locale)->getArrayResult();
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function getSingleResult(QueryBuilder $qb, $hydrationMode = null, $locale = null)
    {
        return $this->getTranslatableQuery($qb, $locale)->getSingleResult($hydrationMode);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function getScalarResult(QueryBuilder $qb, $locale = null)
    {
        return $this->getTranslatableQuery($qb, $locale)->getScalarResult();
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null   $locale A locale name
     */
    public function getSingleScalarResult(QueryBuilder $qb, $locale = null)
    {
        return $this->getTranslatableQuery($qb, $locale)->getSingleScalarResult();
    }

    /**
     * @param string $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->translatableLocale = $locale;
    }

    /**
     * @return string
     */
    public function getTranslatableLocale()
    {
        if (null === $this->translatableLocale) {
            return \Locale::getDefault();
        }

        return $this->translatableLocale;
    }

    /**
     * Returns translated Doctrine query instance
     *
     * @param QueryBuilder $qb     A Doctrine query builder instance
     * @param string       $locale A locale name
     * @return AbstractQuery
     */
    public function getTranslatableQuery(QueryBuilder $qb, $locale = null)
    {
        $query  = $qb->getQuery();
        $locale = $locale ?: $this->getTranslatableLocale();
    
        if ($locale) {
            // Use Translation Walker
            $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, TranslationWalker::class)
            // Force the locale
                ->setHint(TranslatableSubscriber::HINT_TRANSLATABLE_LOCALE, $locale);
        }

        return $query;
    }
}
