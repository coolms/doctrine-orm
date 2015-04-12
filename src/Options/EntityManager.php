<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Options;

use DoctrineORMModule\Options\EntityManager as BaseEntityManagerOptions;

class EntityManager extends BaseEntityManagerOptions
{
    /**
     * Set the connection key for the DiscriminatorMap, which is
     * a service of type {@see \CmsDoctrine\Mapping\DiscriminatorMap\DiscriminatorMapSubscriber}.
     * The DiscriminatorMap service name is assembled
     * as "doctrine.discriminator_map.{key}"
     *
     * @var string
     */
    protected $discriminatorMap = 'orm_default';

    /**
     * Set the connection key for the RelationMap, which is
     * a service of type {@see \CmsDoctrine\Mapping\Relation\RelationSubscriber}.
     * The RelationMap service name is assembled
     * as "doctrine.relation_map.{key}"
     *
     * @var string
     */
    protected $relationMap = 'orm_default';

    /**
     * @param  string $discriminatorMap
     * @return static
     */
    public function setDiscriminatorMap($discriminatorMap)
    {
        $this->discriminatorMap = (string) $discriminatorMap;
        return $this;
    }

    /**
     * @return string
     */
    public function getDiscriminatorMap()
    {
        return "doctrine.discriminator_map.{$this->discriminatorMap}";
    }

    /**
     * @param  string $relationMap
     * @return static
     */
    public function setRelationMap($relationMap)
    {
        $this->relationMap = (string) $relationMap;
        return $this;
    }

    /**
     * @return string
     */
    public function getRelationMap()
    {
        return "doctrine.relation_map.{$this->relationMap}";
    }
}
