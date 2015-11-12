<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Persistence\Filter;

use Doctrine\Common\Persistence\Mapping\ClassMetadata,
    Doctrine\ORM\Query\Expr,
    Doctrine\ORM\QueryBuilder,
    CmsCommon\Persistence\Filter\FilterInterface,
    CmsCommon\Stdlib\ArrayUtils;

/**
 * Data Mapper filter implementation for Doctrine2 ORM
 *
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
class Filter implements FilterInterface
{
    use LikeQueryHelpers;

    /**
     * @var string
     */
    protected $defaultComposite = FilterInterface::ANDX;

    /**
     * @var array
     */
    protected $compositeOperators = [
        self::ANDX,
        self::ORX
    ];

    /**
     * @var array
     */
    protected $comparisonOperators = [
        self::EQUAL,
        self::NOT_EQUAL,
        self::GREATER_THAN,
        self::GREATER_THAN_OR_EQUAL,
        self::LESS_THAN,
        self::LESS_THAN_OR_EQUAL,
        self::IN,
        self::NOT_IN,
        self::IS_NULL,
        self::IS_NOT_NULL,
        self::BETWEEN,
        self::BEGIN_WITH,
        self::NOT_BEGIN_WITH,
        self::END_WITH,
        self::NOT_END_WITH,
        self::CONTAIN,
        self::NOT_CONTAIN,
        self::INSTANCE_OF,
    ];

    /**
     * @var QueryBuilder
     */
    private $qb;

    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     * __construct
     *
     * @param QueryBuilder $qb
     * @param string $operator
     */
    public function __construct(QueryBuilder $qb, $operator = null)
    {
        $this->qb = $qb;

        if (null !== $operator) {
            $this->setDefaultComposite($operator);
        }
    }

    /**
     * Recursively takes the specified criteria and adds to the expression.
     *
     * The criteria is defined in an array notation where each item in the list
     * represents a comparison <fieldName, operator, value>. The operator maps to
     * comparison methods. The key in the array can be used to identify grouping
     * of comparisons.
     *
     * @example
     * $criteria = [
     *      'orX' => [
     *          ['field1', 'contain', 'field1Value'],
     *          ['field2', 'notContain', 'field2Value'],
     *      ],
     *      'andX' => [
     *          ['field3', 'equal', 3],
     *          ['field4', 'equal', 'four'],
     *      ],
     *      ['field5', 'notEqual', 5],
     * ];
     * or
     * $criteria = [
     *      'ORX' => [
     *          ['field1', 'CONTAIN', 'field1Value'],
     *          ['field2', 'NOT_CONTAIN', 'field2Value'],
     *      ],
     *      'ANDX' => [
     *          ['field3', 'EQUAL', 3],
     *          ['field4', 'EQUAL', 'four'],
     *      ],
     *      ['field5', 'NOT_EQUAL', 5],
     * ];
     *
     * $qb = new QueryBuilder();
     * Filter::create($criteria);
     * echo $qb->getSQL();
     *
     * // Result:
     * // SELECT *
     * // FROM tableName
     * // WHERE ((field1 LIKE '%field1Value%') OR (field2 NOT LIKE '%field2Value%'))
     * // AND ((field3 = 3) AND (field4 = 'four'))
     * // AND (field5 <> 5)
     *
     * @param array $criteria
     * @return Expr\Composite
     */
    public function create(array $criteria)
    {
        $expr = $this->getDefaultComposite();
        $this->populate($criteria, $expr);
        return $expr;
    }

    /**
     * @param array $criteria
     * @param Expr\Composite $expr
     * @throws \InvalidArgumentException
     */
    protected function populate(array $criteria, Expr\Composite $expr)
    {
        if ($this->isComparison($criteria)) {
            $expr->add($this->normalizeComparison($criteria));
            return;
        }

        foreach ($criteria as $operatorOrField => $comparisonOrValue) {
            if (!$this->isOperator($operatorOrField)) {
                if ($this->isComparison($comparisonOrValue)) {
                    $comparison = $this->normalizeComparison($comparisonOrValue);
                } else {
                    if (!$this->isField($operatorOrField)) {
                        throw new \InvalidArgumentException(sprintf(
                            'Criteria format is invalid; "%s" is not a valid field for "%s" value',
                            $operatorOrField,
                            is_object($comparisonOrValue)
                                ? get_class($comparisonOrValue)
                                : print_r($comparisonOrValue, true)
                        ));
                    }

                    $operator = is_array($comparisonOrValue) ? static::IN : static::EQUAL;
                    $comparison = $this->$operator($operatorOrField, $comparisonOrValue);
                }

                $expr->add($comparison);
            } elseif ($this->isComposite($operatorOrField)) {
                $operator = $this->normalizeOperator($operatorOrField);
                $composite = $this->$operator();
                $this->populate($comparisonOrValue, $composite);
                $expr->add($composite);
            } else {
                $operator = $this->normalizeOperator($operatorOrField);
                $composite = $this->getDefaultComposite();
                $this->populate($comparisonOrValue, $composite);
                $expr->add($this->$operator($composite));
            }
        }
    }

    /**
     * @param string $field
     * @param mixed $value
     * @return array
     */
    protected function setQueryParam($field)
    {
        $index = 0;
        $rootAlias = $this->qb->getRootAliases()[$index];

        if (false !== strpos($field, '.')) {
            list($assoc, $subField) = explode('.', $field);
        } else {
            $assoc = $field;
        }

        $meta = $this->getClassMetadata($index);
        if ($meta->hasAssociation($assoc)) {
            if ($meta->isCollectionValuedAssociation($assoc)) {
                $alias = "{$rootAlias}_$assoc";
                if (!in_array($alias, $this->qb->getAllAliases())) {
                    $this->qb->leftJoin("$rootAlias.$assoc", $alias);
                }

                $assoc = $alias;
            }

            $targetClass = $meta->getAssociationTargetClass($assoc);
            $em = $this->qb->getEntityManager();
            $targetMeta = $em->getClassMetadata($targetClass);
            if (isset($subField) && !$targetMeta->hasField($subField) && $targetMeta->isInheritanceTypeJoined()) {
                foreach ($targetMeta->discriminatorMap as $alias => $class) {
                    $joinedMeta = $em->getClassMetadata($class);
                    if ($joinedMeta->hasField($subField)) {
                        if (!in_array($alias, $this->qb->getAllAliases())) {
                            $this->qb->leftJoin($joinedMeta->getName(), $alias, 'WITH', "$alias.id = $assoc.id");
                        }

                        $assoc = $alias;
                    }
                }
            }

            $alias = isset($subField) ? "$assoc.$subField" : $assoc;
        } else {
            $alias = "$rootAlias.$field";
        }

        $values = array_slice(func_get_args(), 1);
        if ($values) {
            $result = [$alias];
            foreach ($values as $value) {
                $paramName = $this->getParamName($field);
                $this->qb->setParameter($paramName, $value);
                $result[] = ":$paramName";
            }

            return $result;
        }

        return $alias;
    }

    /**
     * @param int $index
     * @return ClassMetadata
     */
    protected function getClassMetadata($index = 0)
    {
        $rootClasses = $this->qb->getRootEntities();
        return $this->qb->getEntityManager()->getClassMetadata($rootClasses[$index]);
    }

    /**
     * @param string $field
     * @return string
     */
    private function getParamName($field)
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
        } while ($this->qb->getParameter($paramName));

        return $paramName;
    }

    /**
     * @return Expr\Composite
     */
    protected function getDefaultComposite()
    {
        return $this->qb->expr()->{$this->defaultComposite}();
    }

    /**
     * @param string $operator
     * @return self
     */
    public function setDefaultComposite($operator)
    {
        if (!$this->isComposite($operator)) {
            throw new \InvalidArgumentException(sprintf(
                'Only %s are allowed as composite operators; %s given',
                print_r($this->compositeOperators, true),
                $operator
            ));
        }

        $this->defaultComposite = $this->normalizeOperator($operator);
        return $this;
    }

    /**
     * @param string $field
     * @return bool
     */
    public function isField($field)
    {
        if (!is_string($field) || false !== strpos(' ', $field)) {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $comparison
     * @return bool
     */
    public function isComparison($comparison)
    {
        if (!is_array($comparison)) {
            return false;
        }

        if (ArrayUtils::hasStringKeys($comparison) || count($comparison) < 2) {
            return false;
        }

        if (!is_string($comparison[0]) || !is_string($comparison[1])) {
            return false;
        }

        if (!$this->isOperator($comparison[1])) {
            return false;
        }

        $comparison[1] = $this->normalizeOperator($comparison[1]);

        return in_array($comparison[1], $this->comparisonOperators);
    }

    /**
     * @param string $operator
     * @return bool
     */
    public function isComposite($operator)
    {
        if (!$this->isOperator($operator)) {
            return false;
        }

        $operator = $this->normalizeOperator($operator);
        return in_array($operator, $this->compositeOperators);
    }

    /**
     * @param mixed $operator
     * @return bool
     */
    public function isOperator($operator)
    {
        if (!is_string($operator) || false !== strpos(' ', $operator)) {
            return false;
        }

        if (defined("static::$operator")) {
            return true;
        }

        $operators = $this->getOperators();
        return in_array($operator, $operators);
    }

    /**
     * @param string $operator
     * @throws \InvalidArgumentException
     * @return string
     */
    protected function normalizeOperator($operator)
    {
        if (is_string($operator)) {
            if (defined("static::$operator")) {
                $operator = constant("static::$operator");
                if (method_exists($this, $operator)) {
                    return $operator;
                }
            }

            $operators = $this->getOperators();
            if (in_array($operator, $operators, true) && method_exists($this, $operator)) {
                $key = current(array_keys($operators, $operator, true));
                return $operators[$key];
            }
        }

        throw new \InvalidArgumentException(sprintf(
            'Operator %s is not defined',
            is_object($operator) ? get_class($operator) : gettype($operator)
        ));
    }

    /**
     * @param array $comparison
     * @throws \InvalidArgumentException
     * @return Expr\Comparison|Expr\Func|string
     */
    protected function normalizeComparison($comparison)
    {
        if (!$this->isComparison($comparison)) {
            throw new \InvalidArgumentException(sprintf(
                'Argument #1 is not a valid comparison'
            ));
        }

        $operator = $this->normalizeOperator($comparison[1]);
        array_splice($comparison, 1, 1);

        return call_user_func_array([$this, $operator], $comparison);
    }

    /**
     * @return array
     */
    public function getOperators()
    {
        return $this->getReflectionClass()->getConstants();
    }

    /**
     * @return \ReflectionClass
     */
    private function getReflectionClass()
    {
        if (null === $this->reflectionClass) {
            $this->reflectionClass = new \ReflectionClass(get_class());
        }

        return $this->reflectionClass;
    }

    /**
     * @return Expr\Andx
     */
    public function andX()
    {
        $expr = $this->qb->expr()->andX();
        if (func_num_args() === 0) {
            return $expr;
        }

        $comparisons = func_get_args();
        return $expr->addMultiple($comparisons);
    }

    /**
     * @return Expr\Orx
     */
    public function orX()
    {
        $expr = $this->qb->expr()->orX();
        if (func_num_args() === 0) {
            return $expr;
        }

        $comparisons = func_get_args();
        return $expr->addMultiple($comparisons);
    }

    /**
     * {@inheritDoc}
     *
     * @return Expr\Comparison
     */
    public function equal($field, $value)
    {
        list($alias, $param) = $this->setQueryParam($field, $value);
        return $this->qb->expr()->eq($alias, $param);
    }

    /**
     * {@inheritDoc}
     *
     * @return Expr\Comparison
     */
    public function notEqual($field, $value)
    {
        list($alias, $param) = $this->setQueryParam($field, $value);
        return $this->qb->expr()->neq($alias, $param);
    }

    /**
     * {@inheritDoc}
     *
     * @return Expr\Comparison
     */
    public function lessThan($field, $value)
    {
        list($alias, $param) = $this->setQueryParam($field, $value);
        return $this->qb->expr()->lt($alias, $param);
    }

    /**
     * {@inheritDoc}
     *
     * @return Expr\Comparison
     */
    public function lessThanOrEqual($field, $value)
    {
        list($alias, $param) = $this->setQueryParam($field, $value);
        return $this->qb->expr()->lte($alias, $param);
    }

    /**
     * {@inheritDoc}
     *
     * @return Expr\Comparison
     */
    public function greaterThan($field, $value)
    {
        list($alias, $param) = $this->setQueryParam($field, $value);
        return $this->qb->expr()->gt($alias, $param);
    }

    /**
     * {@inheritDoc}
     *
     * @return Expr\Comparison
     */
    public function greaterThanOrEqual($field, $value)
    {
        list($alias, $param) = $this->setQueryParam($field, $value);
        return $this->qb->expr()->gte($alias, $param);
    }

    /**
     * {@inheritDoc}
     *
     * @return Expr\Func
     */
    public function in($field, $value)
    {
        list($alias, $param) = $this->setQueryParam($field, $value);
        return $this->qb->expr()->in($alias, $param);
    }

    /**
     * {@inheritDoc}
     *
     * @return Expr\Func
     */
    public function notIn($field, $value)
    {
        list($alias, $param) = $this->setQueryParam($field, $value);
        return $this->qb->expr()->in($alias, $param);
    }

    /**
     * {@inheritDoc}
     *
     * @return Expr\Func
     */
    public function not($restriction)
    {
        return $this->qb->expr()->not($restriction);
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function isNull($field)
    {
        $alias = $this->setQueryParam($field);
        return $this->qb->expr()->isNull($alias);
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function isNotNull($field)
    {
        $alias = $this->setQueryParam($field);
        return $this->qb->expr()->isNotNull($alias);
    }

    /**
     * @param string $field
     * @param mixed $min
     * @param mixed $max
     * @return Expr\Func
     */
    public function between($field, $min, $max)
    {
        list($alias, $minParam, $maxParam) = $this->setQueryParam($field, $min, $max);
        return $this->qb->expr()->between($alias, $minParam, $maxParam);
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function beginWith($field, $value)
    {
        $value = $this->makeLikeParam($value, '%s%%');
        list($alias, $param) = $this->setQueryParam($field, $value);
        return $this->like($alias, $param);
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function notBeginWith($field, $value)
    {
        $value = $this->makeLikeParam($value, '%s%%');
        list($alias, $param) = $this->setQueryParam($field, $value);
        return $this->notLike($alias, $param);
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function endWith($field, $value)
    {
        $value = $this->makeLikeParam($value, '%%%s');
        list($alias, $param) = $this->setQueryParam($field, $value);
        return $this->like($alias, $param);
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function notEndWith($field, $value)
    {
        $value = $this->makeLikeParam($value, '%%%s');
        list($alias, $param) = $this->setQueryParam($field, $value);
        return $this->notLike($alias, $param);
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function contain($field, $value)
    {
        $value = $this->makeLikeParam($value);
        list($alias, $param) = $this->setQueryParam($field, $value);
        return $this->like($alias, $param);
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function notContain($field, $value)
    {
        $value = $this->makeLikeParam($value);
        list($alias, $param) = $this->setQueryParam($field, $value);
        return $this->notLike($alias, $param);
    }

    /**
     * Creates a LIKE() comparison expression with the given arguments.
     *
     * @param string $field Field in string format to be inspected by LIKE() comparison.
     * @param string $param Argument to be used in LIKE() comparison.
     *
     * @return string
     */
    private function like($field, $param)
    {
        return "$field LIKE $param ESCAPE '!'";
    }

    /**
     * Creates a NOT LIKE() comparison expression with the given arguments.
     *
     * @param string $field Field in string format to be inspected by LIKE() comparison.
     * @param string $param Argument to be used in LIKE() comparison.
     *
     * @return string
     */
    private function notLike($field, $param)
    {
        return "$field NOT LIKE $param ESCAPE '!'";
    }

    /**
     * {@inheritDoc}
     *
     * @return Expr\Comparison
     */
    public function isInstanceOf($field, $value)
    {
        list($alias, $param) = $this->setQueryParam($field, $value);
        return $this->qb->expr()->isInstanceOf($alias, $param);
    }
}
