<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Factory;

use Zend\ServiceManager\ServiceLocatorInterface,
    DoctrineModule\Service\AbstractFactory,
    CmsDoctrine\Tool\InitializerSubscriber,
    CmsDoctrineORM\Options\Initializers;

class InitializersFactory extends AbstractFactory
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $options Initializers */
        $options      = $this->getOptions($serviceLocator, 'initializers');
        $eventManager = $serviceLocator->get($options->getEventManager());
        $initializers = $options->getInitializers();

        foreach ($initializers as $initializer) {
            $subscriber = new InitializerSubscriber($serviceLocator->get($initializer), $serviceLocator);
            $eventManager->addEventSubscriber($subscriber);
        }

        return $eventManager;
    }

    /**
     * Get the class name of the options associated with this factory.
     *
     * @return string
     */
    public function getOptionsClass()
    {
        return Initializers::class;
    }
}
