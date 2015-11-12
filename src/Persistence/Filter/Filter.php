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

use Doctrine\ORM\Query\Expr,
    Doctrine\ORM\QueryBuilder,
    CmsCommon\Persistence\Filter\FilterInterface;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Expr\Composite;
use CmsCommon\Stdlib\ArrayUtils;

/**
 * Data Mapper filter implementation for Doctrine2 ORM
 *
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
class Filter implements FilterInterface
{
    /**
     * @var string
     */
    protected $defaultLogic = FilterInterface::ANDX;

    /**
     * @var array
     */
    protected $logicOperators = [
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
            $this->setDefaultLogic($operator);
        }
    }

    /**
     * @param array $criteria
     * @param QueryBuilder $qb
     * @param ClassMetadata $meta
     * @param Composite $expr
     * @return Composite
     */
    public function create(array $criteria)
    {
        $expr = $this->getDefaultLogic();
        $this->populate($criteria, $expr);
        return $expr;
    }

    /**
     * @param array $criteria
     * @param Composite $expr
     */
    protected function populate(array $criteria, Composite $expr)
    {
        foreach ($criteria as $operatorOrField => $comparisonOrValue) {
            if (!$this->isOperator($operatorOrField)) {
                if (!$this->isComparison($comparisonOrValue)) {
                    $operator = is_array($comparisonOrValue) ? static::IN : static::EQUAL;
                    $comparison = $this->$operator($operatorOrField, $comparisonOrValue);
                } else {
                    $comparison = $this->normalizeComparison($comparisonOrValue);
                }

                $expr->add($comparison);
            } elseif ($this->isLogic($operatorOrField)) {
                $operator = $this->resolveOperator($operatorOrField);
                $composite = $this->$operator();
                $this->populate($comparisonOrValue, $composite);
                $expr->add($composite);
            }
        }
    }

    /**
     * @param string $field
     * @param mixed $value
     * @return array
     */
    protected function populateQuery($field, $value = null)
    {
        $index = 0;
        $rootAlias = $this->qb->getRootAliases()[$index];

        $meta = $this->getClassMetadata($index);
        if ($meta->hasAssociation($field)) {
            if ($meta->isCollectionValuedAssociation($field)) {
                $alias = "{$rootAlias}_$field";
                $this->qb->join("$rootAlias.$field", $alias);
            } else {
                $alias = $field;
            }
        } else {
            $alias = strpos($field, '.') === false ? "$rootAlias.$field" : $field;
        }

        $paramName = $this->getParamName($field);
        $this->qb->setParameter($paramName, $value);

        return [$alias, ":$paramName"];
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
     * @return Composite
     */
    protected function getDefaultLogic()
    {
        $logic = $this->defaultLogic;
        return $this->qb->expr()->$logic();
    }

    /**
     * @param string $operator
     * @return self
     */
    public function setDefaultLogic($operator)
    {
        $defaultLogic = $this->resolveOperator($operator);

        if (!$this->isLogic($operator)) {
            throw new \InvalidArgumentException(sprintf(
                'Only %s are allowed as logic operators; %s given',
                print_r($this->logicOperators, true),
                $operator
            ));
        }

        $this->defaultLogic = $defaultLogic;
        return $this;
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

        return (bool) $this->resolveOperator($comparison[1]);
    }

    /**
     * @param string $operator
     * @return bool
     */
    public function isLogic($operator)
    {
        if (!$this->isOperator($operator)) {
            return false;
        }

        $operator = $this->resolveOperator($operator);
        return in_array($operator, $this->logicOperators);
    }

    /**
     * @param mixed $operator
     * @return bool
     */
    public function isOperator($operator)
    {
        if (!is_string($operator)) {
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
    protected function resolveOperator($operator)
    {
        if (is_string($operator)) {
            if (defined("static::$operator")) {
                return constant("static::$operator");
            }
    
            $operators = $this->getOperators();
            if (in_array($operator, $operators)) {
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

        $operator = $this->resolveOperator($comparison[1]);
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

    public function equal($field, $value)
    {
        
    }

    public function notEqual($field, $value)
    {
        
    }

    public function lessThan($field, $value)
    {
        
    }

    public function lessThanOrEqual($field, $value)
    {
        
    }

    public function greaterThan($field, $value)
    {
        
    }

    public function greaterThanOrEqual($field, $value)
    {
        
    }

    /**
     * {@inheritDoc}
     *
     * @return Expr\Func
     */
    public function in($field, $value)
    {
        list($alias, $paramName) = $this->populateQuery($field, $value);
        return $this->qb->expr()->in($alias, $paramName);
    }

    /**
     * {@inheritDoc}
     *
     * @return Expr\Func
     */
    public function notIn($field, $value)
    {
        list($alias, $paramName) = $this->populateQuery($field, $value);
        return $this->qb->expr()->in($alias, $paramName);
    }

    public function not()
    {
        
    }

    public function isNull($field)
    {
        
    }

    public function isNotNull($field)
    {
        
    }

    public function between($field, $min, $max)
    {
        
    }

    public function beginWith($field, $value)
    {
        
    }

    public function notBeginWith($field, $value)
    {
        
    }

    public function endWith($field, $value)
    {
        
    }

    public function notEndWith($field, $value)
    {
        
    }

    public function contain($field, $value)
    {
        
    }

    public function notContain($field, $value)
    {
        
    }

    public function isInstanceOf($field, $value)
    {
        list($alias, $param) = $this->populateQuery($field, $value);
        return $this->qb->expr()->isInstanceOf($alias, $param);
    }
}
