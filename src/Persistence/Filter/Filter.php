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
class Filter extends Composite implements FilterInterface
{
    /**
     * @var string
     */
    protected $defaultLogic = FilterInterface::ANDX;

    /**
     * @var array
     */
    private $logicOperators = [
        self::ANDX,
        self::ORX
    ];

    /**
     * @var QueryBuilder
     */
    private $qb;

    /**
     * @var ClassMetadata
     */
    private $meta;

    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     * __construct
     *
     * @param array $criteria
     * @param QueryBuilder $qb
     * @param ClassMetadata $class
     */
    public function __construct($criteria = [], QueryBuilder $qb, ClassMetadata $meta)
    {
        $this->qb = $qb;
        $this->meta = $meta;

        if ($criteria) {
            $this->exchangeArray($criteria);
        }
    }

    /**
     * @param array $criteria
     */
    public function exchangeArray(array $criteria)
    {
        if (!ArrayUtils::hasStringKeys($criteria)) {
            $expr = $this->getDefaultLogic();
        } else {
            $expr = $this;
        }

        foreach ($criteria as $operator => $comparison) {
            
        }
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
     * @param string $operator
     * @return bool
     */
    public function isLogic($operator)
    {
        $operator = $this->resolveOperator($operator);
        return in_array($operator, $this->logicOperators);
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

    public function getArrayCopy()
    {
        
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

    public function equal($field, $value);

    public function notEqual($field, $value);

    public function lessThan($field, $value);

    public function lessThanOrEqual($field, $value);

    public function greaterThan($field, $value);

    public function greaterThanOrEqual($field, $value);

    public function in($field, $value);

    public function notIn($field, $value);

    public function not();

    public function isNull($field);

    public function isNotNull($field);

    public function beginWith($field, $value);

    public function notBeginWith($field, $value);

    public function endWith($field, $value);

    public function notEndWith($field, $value);

    public function contain($field, $value);

    public function notContain($field, $value);

    public function instanceOfX($field, $value);
}
