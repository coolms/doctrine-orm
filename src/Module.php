<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM;

use Zend\Loader\ClassMapAutoloader,
    Zend\Loader\StandardAutoloader,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\ModuleManager\Feature\InitProviderInterface,
    Zend\ModuleManager\ModuleEvent,
    Zend\ModuleManager\ModuleManagerInterface,
    Doctrine\Common\Proxy\Autoloader as ProxyAutoloader;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    InitProviderInterface
{
    /**
     * @param ModuleManagerInterface $moduleManager
     */
    public function init(ModuleManagerInterface $moduleManager)
    {
        $moduleManager->loadModule('DoctrineModule');
        $moduleManager->loadModule('DoctrineORMModule');
        $moduleManager->loadModule('CmsDoctrine');

        $em = $moduleManager->getEventManager();
        $em->attach(ModuleEvent::EVENT_LOAD_MODULES_POST, [$this, 'onPostLoadModules']);
    }

    /**
     * @param ModuleEvent $e
     */
    public function onPostLoadModules(ModuleEvent $e)
    {
        $configListener = $e->getConfigListener();
        $config         = $configListener->getMergedConfig(false);

        if (isset($config['doctrine']['configuration']['orm_default']['proxy_dir']) &&
            isset($config['doctrine']['configuration']['orm_default']['proxy_namespace'])) {
            // We need to register here manualy. Please see http://www.doctrine-project.org/jira/browse/DDC-1698
            ProxyAutoloader::register(
                $config['doctrine']['configuration']['orm_default']['proxy_dir'],
                $config['doctrine']['configuration']['orm_default']['proxy_namespace']
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return [
            ClassMapAutoloader::class => [
                __DIR__ . '/../autoload_classmap.php',
            ],
            StandardAutoloader::class => [
                'fallback_autoloader' => true,
                'namespaces' => [
                    __NAMESPACE__ => __DIR__,
                ],
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
