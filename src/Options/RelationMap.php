<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Options;

use Zend\Stdlib\AbstractOptions;

class RelationMap extends AbstractOptions
{
    /**
     * Set the configuration key for the EventManager. Event manager key
     * is assembled as "doctrine.eventmanager.{key}" and pulled from
     * service locator.
     *
     * @var string
     */
    protected $eventManager = 'orm_default';

    /**
     * An array that maps an object type to association mapping
     *
     * @var array
     */
    protected $mappings = [];

    /**
     * @param  string $eventManager
     * @return self
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;

        return $this;
    }

    /**
     * @return string
     */
    public function getEventManager()
    {
        return "doctrine.eventmanager.{$this->eventManager}";
    }

    /**
     * @param  array $maps
     * @return static
     */
    public function setMappings(array $mappings)
    {
        foreach ($mappings as $objectType => $mapping) {
            $this->mappings[$objectType] = (array) $mapping;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getMappings()
    {
        return $this->mappings;
    }
}
