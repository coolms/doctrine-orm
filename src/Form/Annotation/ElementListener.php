<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Form\Annotation;

use Zend\EventManager\AbstractListenerAggregate,
    Zend\EventManager\EventManagerInterface,
    Zend\Form\Annotation\ComposedObject,
    Doctrine\Common\Persistence\ObjectManager,
    DoctrineModule\Form\Element\ObjectSelect,
    DoctrineModule\Persistence\ProvidesObjectManager;

class ElementListener extends AbstractListenerAggregate
{
    use ProvidesObjectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('configureElement', [$this, 'handleAllowEmpty']);
    }

    /**
     * @param \Zend\EventManager\EventInterface $e
     */
    public function handleAllowEmpty($e)
    {
        $elementSpec = $e->getParam('elementSpec');
        /*if ($elementSpec['spec']['name']) {
            var_dump($elementSpec);
        }*/
    }
}
