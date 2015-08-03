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
    DoctrineORMModule\Service\ConfigurationFactory as DoctrineORMConfigurationFactory,
    CmsDoctrineORM\Tool\TablePrefixSubscriber;

class ConfigurationFactory extends DoctrineORMConfigurationFactory
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $options \CmsDoctrineORM\Options\Configuration */
        $options = $this->getOptions($serviceLocator);

        if ($tablePrefix = $options->getTablePrefix()) {
            $eventManager = $serviceLocator->get($options->getEventManager());
            $subscriber   = new TablePrefixSubscriber($tablePrefix);
            $eventManager->addEventSubscriber($subscriber);
        }

        return parent::createService($serviceLocator);
    }

    /**
     * {@inheritDoc}
     */
    protected function getOptionsClass()
    {
        return 'CmsDoctrineORM\\Options\\Configuration';
    }
}
