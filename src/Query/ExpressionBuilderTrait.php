<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Query;

use Doctrine\ORM\Query\Expr\Composite as CompositeExpression,
    Doctrine\ORM\Query\Expr\OrderBy as OrderByExpression,
    Doctrine\ORM\QueryBuilder;

/**
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait ExpressionBuilderTrait
{
    /**
     * Recursively takes the specified criteria and adds to the expression.
     *
     * The criteria is defined in an array notation where each item in the list
     * represents a comparison <fieldName, operator, value>. The operator maps to
     * comparison methods located in ExpressionBuilder. The key in the array can
     * be used to identify grouping of comparisons.
     *
     * @example
     * $criteria = [
     *      'or' => [
     *          ['field1', 'like', '%field1Value%'],
     *          ['field2', 'like', '%field2Value%'],
     *      ],
     *      'and' => [
     *          ['field3', 'eq', 3],
     *          ['field4', 'eq', 'four'],
     *      ],
     *      array['field5', 'neq', 5],
     * ];
     *
     * $qb = new QueryBuilder();
     * buildExpr($qb, $qb->expr()->andX(), $criteria);
     * echo $qb->getSQL();
     *
     * // Result:
     * // SELECT *
     * // FROM tableName
     * // WHERE ((field1 LIKE '%field1Value%') OR (field2 LIKE '%field2Value%'))
     * // AND ((field3 = '3') AND (field4 = 'four'))
     * // AND (field5 <> '5')
     *
     * @param QueryBuilder $qb
     * @param CompositeExpression $expr
     * @param array $criteria
     * @return CompositeExpression
     */
    protected function buildExpr(QueryBuilder $qb, CompositeExpression $expr, array $criteria)
    {
        if (!$criteria) {
            return $expr;
        }

        foreach ($criteria as $expression => $comparison) {
            if ($expression === 'or') {
                $expr->add($this->buildExpr(
                    $qb,
                    $qb->expr()->orX(),
                    $comparison
                ));
            } elseif ($expression === 'and') {
                $expr->add($this->buildExpr(
                    $qb,
                    $qb->expr()->andX(),
                    $comparison
                ));
            } else {
                $comparison = $this->formatComparison($comparison, $expression, $qb);
                list($field, $operator, $value) = $comparison;
                if (null === $field && null === $operator) {
                    $expr->add($value);
                } else {
                    $param = str_replace('.', '_', $field);
                    if (!is_callable([$qb->expr(), $operator])) {
                        $expr->add("$field $operator :$param");
                    } else {
                        if (strpos($field, '.') === false) {
                            $rootAlias  = $qb->getRootAlias();
                            $field      = "$rootAlias.$field";
                        }
                        $expr->add($qb->expr()->{$operator}($field, ":$param"));
                    }
                    $qb->setParameter($param, $value);
                }
            }
        }

        return $expr;
    }

    /**
     * @param QueryBuilder $qb
     * @param OrderByExpression $expr
     * @param array|string $orderBy
     * @param array|string $direction
     * @return self
     */
    protected function buildOrderByExpr(QueryBuilder $qb, OrderByExpression $expr = null,
        $orderBy = null, $direction = null)
    {
        if (null === $orderBy) {
            return;
        }

        $orderBy = (array) $orderBy;
        if (null !== $direction) {
            if (is_string($direction)) {
                $direction = array_fill(0, count($orderBy), $direction);
            }
            $direction = (array) $direction;
            
            if (count($orderBy) === count($direction)) {
                $orderBy = array_combine($orderBy, $direction);
            } else {
                throw new \InvalidArgumentException('Invalid sort options specified');
            }
        }
        if (null === $expr) {
        	$expr = new OrderByExpression();
        }
        foreach ($orderBy as $field => $direction) {
            if (strpos($field, '.') === false) {
            	$rootAlias = $qb->getRootAlias();
            	$field     = "$rootAlias.$field";
            }
            $expr->add($field, $direction);
        }

        return $expr;
    }

    /**
     * @param mixed $comparison
     * @param string|int $expression
     * @param QueryBuilder $qb
     * @return array
     */
    private function formatComparison($comparison, $expression, QueryBuilder $qb)
    {
        if (is_array($comparison) && is_int($expression)) {
            if (count($comparison) !== 3) {
                $comparison = [null, null, $comparison];
            } elseif (isset($comparison[1]) && !is_callable([$qb->expr(), $comparison[1]])) {
                $filter = new \Zend\Filter\Word\SeparatorToCamelCase();
                $className = 'Doctrine\\ORM\\Query\\AST\\' . $filter->filter($comparison[1]) . 'Expression';
                if (class_exists($className)) {
                    $comparison[1] = strtoupper($comparison[1]);
                }
            }
        } elseif (!is_int($expression)) {
            $comparison = [$expression, is_array($comparison) ? 'in' : 'eq', $comparison];
        } elseif (is_string($comparison)) {
            $comparison = [null, null, $comparison];
        }

        return $comparison;
    }
}
