<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Hierarchy\Repository;

use Doctrine\ORM\AbstractQuery,
    Doctrine\ORM\QueryBuilder,
    CmsCommon\Mapping\Common\ObjectableInterface,
    CmsDoctrineORM\Mapping\Common\Repository\EntityRepositoryTrait,
    CmsDoctrineORM\Mapping\Translatable\Repository\TranslatableRepositoryInterface;

trait HierarchyRepositoryTrait
{
    use EntityRepositoryTrait;

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'node';
    }

    /**
     * @param object|string $node
     * @param array|bool    $criteria
     * @param string|array  $orderBy
     * @param string|array  $direction
     * @param bool          $includeNode
     * @param array         $options
     * @return QueryBuilder
     */
    public function childrenQueryBuilder($node = null, $criteria = false, $orderBy = null,
            $direction = 'ASC', $includeNode = false, array $options = [])
    {
        $className = $this->getClassName();
        if (is_object($node) && !$node instanceof $className) {
            $node = $this->findObjectNode($node, isset($options['root']) ? $options['root'] : null);
        }

        $qb = parent::childrenQueryBuilder($node, ($criteria === true), $orderBy, $direction, $includeNode);

        if (is_a($className, ObjectableInterface::class, true)) {
            $qb->addSelect('object')
               ->innerJoin($this->getAlias() . '.object', 'object');
        }

        if (is_array($criteria)) {
            $qb->andWhere($this->getFilter($qb)->create($criteria));
            //$qb->andWhere($this->buildExpr($criteria, $qb));
        }

        if (null !== $orderBy) {
            $qb->orderBy($this->buildOrderByExpr($orderBy, $direction, $qb));
        }

        return $qb;
    }

    /**
     * {@inheritDoc}
     */
    public function getChildrenQueryBuilder($node = null, $direct = false, $orderBy = null,
            $direction = 'ASC', $includeNode = false, array $options = [])
    {
        return $this->childrenQueryBuilder($node, $direct, $orderBy, $direction, $includeNode, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function childrenQuery($node = null, $direct = false, $orderBy = null, $direction = 'ASC',
            $includeNode = false, array $options = [])
    {
        $qb = $this->childrenQueryBuilder($node, $direct, $orderBy, $direction, $includeNode, $options);
        if ($this instanceof TranslatableRepositoryInterface) {
            return $this->getTranslatableQuery($qb, empty($options['locale']) ? null : $options['locale']);
        }

        return $qb->getQuery();
    }

    /**
     * {@inheritDoc}
     */
    public function getChildrenQuery($node = null, $direct = false, $orderBy = null, $direction = 'ASC',
            $includeNode = false, array $options = [])
    {
        return $this->childrenQuery($node, $direct, $orderBy, $direction, $includeNode, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function children($node = null, $direct = false, $orderBy = null, $direction = 'ASC',
            $includeNode = false, array $options = [], $hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        return $this->childrenQuery($node, $direct, $orderBy, $direction, $includeNode, $options)
            ->getResult($hydrationMode);
    }

    /**
     * {@inheritDoc}
     */
    public function getChildren(
        $node = null,
        $direct = false,
        $orderBy = null,
        $direction = 'ASC',
        $includeNode = false,
        array $options = [],
        $hydrationMode = AbstractQuery::HYDRATE_OBJECT
    ) {
        return $this->children($node, $direct, $orderBy, $direction, $includeNode, $options, $hydrationMode);
    }

    /**
     * @param object|string $node
     * @param bool $direct
     * @param array $options
     * @param bool $includeNode
     * @return QueryBuilder
     */
    abstract public function getNodesHierarchyQueryBuilder($node, $direct, $options, $includeNode);

    /**
     * @param object|string $node
     * @param bool $direct
     * @param array $options
     * @param bool $includeNode
     * @return AbstractQuery
     */
    public function getNodesHierarchyQuery(
        $node = null,
        $direct = false,
        array $options = [],
        $includeNode = false
    ) {
        $qb = $this->getNodesHierarchyQueryBuilder($node, $direct, $options, $includeNode);
        if ($this instanceof TranslatableRepositoryInterface) {
            return $this->getTranslatableQuery($qb, empty($options['locale']) ? null : $options['locale']);
        }

        return $qb->getQuery();
    }

    /**
     * @param object $object
     * @param string $root
     * @return QueryBuilder
     */
    public function getFindObjectNodeQueryBuilder($object, $root = null)
    {
        $criteria = compact('object');

        if (is_string($root)) {
            $meta = $this->getClassMetadata();
            $config = $this->listener->getConfiguration($this->_em, $meta->name);
            if (isset($config['root'])) {
                $criteria['root'] = $root;
            }
        }

        return $this->findOneByQueryBuilder($criteria);
    }

    /**
     * @param object $object
     * @param string $root
     * @return Query
     */
    public function getFindObjectNodeQuery($object, $root = null)
    {
        return $this->getFindObjectNodeQueryBuilder($object, $root)->getQuery();
    }

    /**
     * @param object $object
     * @param string $root
     * @return object|null
     */
    public function findObjectNode($object, $root = null)
    {
        return $this->getFindObjectNodeQuery($object, $root)->getOneOrNullResult();
    }
}
