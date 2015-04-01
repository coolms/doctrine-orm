<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/CmsDoctrineORM for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Options;

use Zend\Stdlib\AbstractOptions;

class DiscriminatorMap extends AbstractOptions
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
     * An array that maps an discriminator entry (type) to class name
     *
     * @var array
     */
    protected $maps = [];

    /**
     * @param  string $eventManager
     * @return static
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
    public function setMaps(array $maps)
    {
        foreach ($maps as $entity => $map) {
            $this->maps[$entity] = (array) $map;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getMaps()
    {
        return $this->maps;
    }
}
