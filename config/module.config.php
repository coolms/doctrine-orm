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

return [
    'doctrine' => [
        'cache' => [
            'filesystem' => [
                'class' => 'Doctrine\Common\Cache\FilesystemCache',
                'directory' => '/../../data/DoctrineModule/cache',
            ],
        ],
        'config_cache_enabled' => true,
        'configuration' => [
            'orm_default' => [
                'metadata_cache' => 'array',
                'query_cache' => 'array',
                'result_cache' => 'array',
                'naming_strategy' => 'CmsDoctrineORM\Mapping\DefaultNamingStrategy',
                'generate_proxies' => \Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_ALWAYS,
                'proxy_dir' => 'data/DoctrineORMModule/Proxy',
                'proxy_namespace' => 'DoctrineORMModule\Proxy',
                'datetime_functions' => [
                    'YEAR' => 'DoctrineExtensions\Query\Mysql\Year',
                    'IFNULL' => 'DoctrineExtensions\Query\Mysql\IfNull',
                    'DATE' => 'CmsDoctrineORM\Query\Mysql\Date',
                ],
            ],
        ],
        'discriminator_map' => [
            'orm_default' => [
                
            ],
        ],
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    'CmsDoctrine\Mapping\Relation\RelationSubscriber'
                        => 'CmsDoctrine\Mapping\Relation\RelationSubscriber',
                    'CmsDoctrine\Mapping\ElementCollection\ElementCollectionSubscriber'
                        => 'CmsDoctrine\Mapping\ElementCollection\ElementCollectionSubscriber',
                    'CmsDoctrine\Mapping\Metadatable\MetadatableSubscriber'
                        => 'CmsDoctrine\Mapping\Metadatable\MetadatableSubscriber',
                    'CmsDoctrine\Mapping\Dateable\TimestampableSubscriber'
                        => 'CmsDoctrine\Mapping\Dateable\TimestampableSubscriber',
                    'CmsDoctrine\Mapping\Hierarchy\HierarchySubscriber'
                        => 'CmsDoctrine\Mapping\Hierarchy\HierarchySubscriber',
                    'Gedmo\Sluggable\SluggableListener'
                        => 'Gedmo\Sluggable\SluggableListener',
                    'CmsDoctrine\Mapping\Translatable\TranslatableSubscriber'
                        => 'CmsDoctrine\Mapping\Translatable\TranslatableSubscriber',
                ],
            ],
        ],
        /*'driver' => [
            'translatable_metadata_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    'vendor/gedmo/doctrine-extensions/lib/Gedmo/Translatable/Entity',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    'Gedmo\Translatable\Entity' => 'translatable_metadata_driver',
                ],
            ],
        ],*/
    ],
    'doctrine_factories' => [
        'entitymanager' => 'CmsDoctrineORM\Service\EntityManagerFactory',
        'discriminator_map' => 'CmsDoctrineORM\Service\DiscriminatorMapFactory',
    ],
    'form_elements' => [
        'abstract_factories' => [
            'CmsDoctrineORM\Form\Annotation\FormAbstractServiceFactory'
                => 'CmsDoctrineORM\Form\Annotation\FormAbstractServiceFactory',
        ],
    ],
    'listeners' => [
        'CmsDoctrineORM\Event\TablePrefixListener'
            => 'CmsDoctrineORM\Event\TablePrefixListener',
    ],
    'mappers' => [
        'abstract_factories' => [
            'CmsDoctrineORM\Persistence\MapperAbstractServiceFactory'
                => 'CmsDoctrineORM\Persistence\MapperAbstractServiceFactory',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'CmsDoctrineORM\Event\TablePrefixListener'
                => 'CmsDoctrineORM\Event\TablePrefixListener',
            'CmsDoctrineORM\Mapping\DefaultNamingStrategy'
                => 'CmsDoctrineORM\Mapping\DefaultNamingStrategy',
        ],
    ],
    'session_containers' => [
        'abstract_factories' => [
            'CmsDoctrineORM\Session\ContainerAbstractServiceFactory'
                => 'CmsDoctrineORM\Session\ContainerAbstractServiceFactory',
        ],
    ],
];
