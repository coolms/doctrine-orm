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
                'class'     => 'Doctrine\Common\Cache\FilesystemCache',
                'directory' => '/../../data/DoctrineModule/cache',
            ],
        ],
        'config_cache_enabled' => true,
        'configuration' => [
            'orm_default' => [
                'metadata_cache'     => 'array',
                'query_cache'        => 'array',
                'result_cache'       => 'array',
                'naming_strategy'    => 'CmsDoctrineORM\Mapping\DefaultNamingStrategy',
                'generate_proxies'   => \Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_ALWAYS,
                'proxy_dir'          => 'data/DoctrineORMModule/Proxy',
                'proxy_namespace'    => 'DoctrineORMModule\Proxy',
                'datetime_functions' => [
                    'YEAR'   => 'DoctrineExtensions\Query\Mysql\Year',
                    'IFNULL' => 'DoctrineExtensions\Query\Mysql\IfNull',
                    'DATE'   => 'CmsDoctrineORM\Query\Mysql\Date',
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
        'relation_map' => [
            'orm_default' => [
                
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
        'entitymanager'     => 'CmsDoctrineORM\Service\EntityManagerFactory',
        'discriminator_map' => 'CmsDoctrineORM\Service\DiscriminatorMapFactory',
        'relation_map'      => 'CmsDoctrineORM\Service\RelationMapFactory',
    ],
    'form_elements' => [
        'abstract_factories' => [
            'CmsDoctrineORM\EntityForm' => 'CmsDoctrineORM\Form\Annotation\FormAbstractServiceFactory',
        ],
    ],
    'listeners' => [
        'CmsDoctrineORM\EventListener\TablePrefixListener' => 'CmsDoctrineORM\EventListener\TablePrefixListener',
    ],
    'mappers' => [
        'abstract_factories' => [
            'CmsDoctrineORM\Mapper' => 'CmsDoctrineORM\Persistence\MapperAbstractServiceFactory',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'CmsDoctrineORM\EventListener\TablePrefixListener' => 'CmsDoctrineORM\EventListener\TablePrefixListener',
            'CmsDoctrineORM\Mapping\DefaultNamingStrategy'     => 'CmsDoctrineORM\Mapping\DefaultNamingStrategy',
        ],
    ],
    'session_containers' => [
        'abstract_factories' => [
            'CmsDoctrineORM\SessionContainer' => 'CmsDoctrineORM\Session\ContainerAbstractServiceFactory',
        ],
    ],
];
