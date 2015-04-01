<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Service;

use Zend\ServiceManager\ServiceLocatorInterface,
    DoctrineModule\Service\AbstractFactory,
    CmsDoctrine\Mapping\DiscriminatorMap\DiscriminatorMapSubscriber;

class DiscriminatorMapFactory extends AbstractFactory
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $options \CmsDoctrineORM\Options\DiscriminatorMap */
        $options      = $this->getOptions($serviceLocator, 'discriminator_map');
        $eventManager = $serviceLocator->get($options->getEventManager());
        $maps         = $options->getMaps();

        $subscriber   = new DiscriminatorMapSubscriber($maps);
        $eventManager->addEventSubscriber($subscriber);

        return $eventManager;
    }

    /**
     * Get the class name of the options associated with this factory.
     *
     * @return string
     */
    public function getOptionsClass()
    {
        return 'CmsDoctrineORM\\Options\\DiscriminatorMap';
    }
}
