<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Options;

use Zend\Stdlib\AbstractOptions;

class Initializers extends AbstractOptions
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
     * @var array
     */
    protected $initializers = [];

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
     * @param  array $initializers
     * @return self
     */
    public function setInitializers(array $initializers)
    {
        $this->initializers = $initializers;

        return $this;
    }

    /**
     * @return array
     */
    public function getInitializers()
    {
        return $this->initializers;
    }

    /**
     * @param string $method
     * @param array $args
     */
    public function __call($method, $args)
    {
        $this->initializers[$method] = $args[0];
    }
}
