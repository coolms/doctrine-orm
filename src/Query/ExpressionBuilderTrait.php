<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Query;

use Zend\Filter\Word\SeparatorToCamelCase,
    Doctrine\ORM\Mapping\ClassMetadata,
    Doctrine\ORM\Query\Expr\Composite as CompositeExpression,
    Doctrine\ORM\Query\Expr\OrderBy as OrderByExpression,
    Doctrine\ORM\QueryBuilder,
    CmsCommon\Persistence\MapperInterface;

/**
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait ExpressionBuilderTrait
{
    /**
     * Mapping human-readable operators to Expr methods
     *
     * @var array
     */
    protected $exprOperators = [
        MapperInterface::OP_AND                     => 'and',
        MapperInterface::OP_EQUAL                   => 'eq',
        MapperInterface::OP_NOT_EQUAL               => 'neq',
        MapperInterface::OP_LESS_THAN               => 'lt',
        MapperInterface::OP_LESS_THAN_OR_EQUAL      => 'lte',
        MapperInterface::OP_GREATER_THAN            => 'gt',
        MapperInterface::OP_GREATER_THAN_OR_EQUAL   => 'gte',
        MapperInterface::OP_BEGIN_WITH              => 'like',
        MapperInterface::OP_NOT_BEGIN_WITH          => 'notLike',
        MapperInterface::OP_IN                      => 'in',
        MapperInterface::OP_NOT_IN                  => 'notIn',
        MapperInterface::OP_NULL                    => 'isNull',
        MapperInterface::OP_NOT_NULL                => 'isNotNull',
        MapperInterface::OP_END_WITH                => 'like',
        MapperInterface::OP_NOT_END_WITH            => 'notLike',
        MapperInterface::OP_CONTAIN                 => 'like',
        MapperInterface::OP_NOT_CONTAIN             => 'notLike',
        MapperInterface::OP_OR                      => 'or',
    ];

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
     *          ['field2', 'notLike', '%field2Value%'],
     *      ],
     *      'and' => [
     *          ['field3', 'eq', 3],
     *          ['field4', 'eq', 'four'],
     *      ],
     *      ['field5', 'neq', 5],
     * ];
     * or
     * $criteria = [
     *      'OR' => [
     *          ['field1', 'CONTAIN', 'field1Value'],
     *          ['field2', 'NOT_CONTAIN', 'field2Value'],
     *      ],
     *      'AND' => [
     *          ['field3', 'EQUAL', 3],
     *          ['field4', 'EQUAL', 'four'],
     *      ],
     *      ['field5', 'NOT_EQUAL', 5],
     * ];
     *
     * $qb = new QueryBuilder();
     * buildExpr($qb, $qb->expr()->andX(), $criteria, $meta);
     * echo $qb->getSQL();
     *
     * // Result:
     * // SELECT *
     * // FROM tableName
     * // WHERE ((field1 LIKE '%field1Value%') OR (field2 NOT LIKE '%field2Value%'))
     * // AND ((field3 = 3) AND (field4 = 'four'))
     * // AND (field5 <> 5)
     *
     * @param QueryBuilder $qb
     * @param CompositeExpression $expr
     * @param array $criteria
     * @return CompositeExpression
     */
    protected function buildExpr(QueryBuilder $qb, CompositeExpression $expr, array $criteria)
    {
        $meta = $this->getClassMetadata();

        foreach ($criteria as $expression => $comparison) {
            if ($this->getExprOperator($expression) === 'or') {
                $expr->add($this->buildExpr($qb, $qb->expr()->orX(), $comparison));
                continue;
            }

            if ($this->getExprOperator($expression) === 'and') {
                $expr->add($this->buildExpr($qb, $qb->expr()->andX(), $comparison));
                continue;
            }

            list($field, $operator, $value) = $this->formatComparison($comparison, $expression, $qb);

            if (null === $field && null === $operator) {
                $expr->add($value);
                continue;
            }

            $alias      = '';
            $rootAlias  = $qb->getRootAlias();
            $paramName  = $this->getParamName($field, $qb);
            $operator   = $this->getExprOperator($operator);
            $value      = $this->setWildCardInValue($operator, $value);

            if ($meta->hasAssociation($field) && !$meta->isSingleValuedAssociation($field)) {
                $alias = "{$rootAlias}_$field";
                $qb->join("$rootAlias.$field", $alias);
            }

            if (is_callable([$qb->expr(), $operator])) {
                if (!$alias) {
                    $alias = strpos($field, '.') === false ? "$rootAlias.$field" : $field;
                }

                if ($operator === 'isNull' || $operator === 'isNotNull') {
                    $expr->add($qb->expr()->{$operator}($alias));
                    continue;
                } else {
                    $expr->add($qb->expr()->{$operator}($alias, ":$paramName"));
                }
            } else {
                $expr->add(($alias ?: $field) . " $operator :$paramName");
            }

            $qb->setParameter($paramName, $value);
        }

        return $expr;
    }

    /**
     * @param string $field
     * @param QueryBuilder $qb
     * @return string
     */
    private function getParamName($field, QueryBuilder $qb)
    {
        $paramName = str_replace('.', '_', $field);
        $counter = 0;

        do {
            $index = substr($paramName, strrpos($paramName, '_') + 1);
            if (is_numeric($index)) {
                $paramName = substr_replace($paramName, $counter, strrpos($paramName, '_') + 1);
                $counter++;
            } else {
                $paramName .= "_$counter";
            }
        } while ($qb->getParameter($paramName));

        return $paramName;
    }

    /**
     * @param QueryBuilder $qb
     * @param OrderByExpression $expr
     * @param array|string $orderBy
     * @param array|string $direction
     * @return OrderByExpression
     */
    protected function buildOrderByExpr(
        QueryBuilder $qb,
        OrderByExpression $expr = null,
        $orderBy = null,
        $direction = null
    ) {
        if (null === $expr) {
            $expr = new OrderByExpression();
        }

        if (null === $orderBy) {
            return $expr;
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

        foreach ($orderBy as $field => $direction) {
            if (false === strpos($field, '.')) {
            	$rootAlias = $qb->getRootAlias();
            	$field     = "$rootAlias.$field";
            }

            $expr->add($field, $direction);
        }

        return $expr;
    }

    /**
     * @return ClassMetadata
     */
    abstract public function getClassMetadata();

    /**
     * @param string $alias
     * @return string
     */
    public function getExprOperator($alias)
    {
        if (isset($this->exprOperators[$alias])) {
            return $this->exprOperators[$alias];
        }

        return $alias;
    }

    /**
     * Place wildcard filtering in value
     *
     * @param string $expression expression to filter
     * @param string $value      value to add wildcard to
     * @return string
     */
    protected function setWildCardInValue($expression, $value)
    {
        switch (strtoupper($expression)) {
            case MapperInterface::OP_BEGIN_WITH:
            case MapperInterface::OP_NOT_BEGIN_WITH:
                return "$value%";
            case MapperInterface::OP_END_WITH:
            case MapperInterface::OP_NOT_END_WITH:
                return "%$value";
            case MapperInterface::OP_CONTAIN:
            case MapperInterface::OP_NOT_CONTAIN:
                return "%$value%";
            default:
                return $value;
        }
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
                $filter = new SeparatorToCamelCase();
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
