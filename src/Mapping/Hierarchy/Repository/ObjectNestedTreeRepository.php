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

use Doctrine\ORM\Query,
    Doctrine\ORM\QueryBuilder;

/**
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
class ObjectNestedTreeRepository extends NestedTreeRepository
{
    /**
     * @param array     $criteria
     * @param array     $orderBy
     * @param int       $limit
     * @param int       $offset
     * @return QueryBuilder
     */
    public function findByQueryBuilder(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this->createQueryBuilder('node');
        $qb->addSelect('object')
           ->innerJoin('node.object', 'object')
           ->where($this->buildExpr($qb, $qb->expr()->andX(), $criteria, $this->getClassMetadata()));

        if (null !== $orderBy) {
            $qb->orderBy($this->buildOrderByExpr($qb, null, $orderBy, null, $this->getClassMetadata()));
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
     * @param object        $node
     * @param bool          $direct
     * @param string|array  $orderBy
     * @param string|array  $direction
     * @param bool          $includeNode
     * @param array         $options
     * @return QueryBuilder
     */
    public function childrenQueryBuilder($node = null, $direct = false, $orderBy = null,
            $direction = 'ASC', $includeNode = false, array $options = [])
    {
        $className = $this->getClassName();
        if (is_object($node) && !$node instanceof $className) {
            $node = $this->findObjectNode($node, isset($options['root']) ? $options['root'] : null);
        }

        $qb = parent::childrenQueryBuilder($node, $direct, $orderBy, $direction, $includeNode, $options);
        $qb->addSelect('object')
           ->innerJoin('node.object', 'object');

        if (!empty($options['criteria'])) {
            $qb->andWhere($this->buildExpr($qb, $qb->expr()->andX(), $options['criteria'], $this->getClassMetadata()));
        }

        if (null !== $orderBy) {
            $qb->orderBy($this->buildOrderByExpr($qb, null, $orderBy, $direction, $this->getClassMetadata()));
        }

        return $qb;
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
