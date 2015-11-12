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

use Doctrine\Common\Proxy\AbstractProxyFactory,
    Doctrine\ORM\Mapping\Driver\AnnotationDriver,
    CmsDoctrine\DBAL\Types\DecimalObject,
    CmsDoctrineORM\Mapping\DefaultNamingStrategy,
    CmsDoctrineORM\Mapping\Common\Repository\EntityRepository;

return [
    'doctrine' => [
        'cache' => [
            'filesystem' => [
                'class' => 'Doctrine\Common\Cache\FilesystemCache',
                'directory' => 'data/DoctrineModule/cache',
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'table_prefix' => 'cms_',
                'hydration_cache' => 'array',
                'metadata_cache' => 'array',
                'query_cache' => 'array',
                'result_cache' => 'array',
                'naming_strategy' => DefaultNamingStrategy::class,
                'generate_proxies' => AbstractProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS,
                'proxy_dir' => 'data/DoctrineORMModule/Proxy',
                'proxy_namespace' => 'DoctrineORMModule\Proxy',
                'default_repository_class_name' => EntityRepository::class,
                'datetime_functions' => [
                    'YEAR' => 'DoctrineExtensions\Query\Mysql\Year',
                    'IFNULL' => 'DoctrineExtensions\Query\Mysql\IfNull',
                    'DATE' => 'DoctrineExtensions\Query\Mysql\Date',
                ],
                'types' => [
                    'decimal_object' => DecimalObject::class,
                ],
            ],
        ],
        'discriminator_map' => [
            'orm_default' => [
                
            ],
        ],
        'driver' => [
            'cmsdoctrineorm_dateable_metadata_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/Mapping/Dateable',
            ],
            'cmsdoctrineorm_translatable_metadata_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/Mapping/Translatable',
            ],
            'orm_default' => [
                'drivers' => [
                    'CmsDoctrineORM\Mapping\Dateable' => 'cmsdoctrineorm_dateable_metadata_driver',
                    'CmsDoctrineORM\Mapping\Translatable' => 'cmsdoctrineorm_translatable_metadata_driver',
                ],
            ],
        ],
        'entity_resolver' => [
            'orm_default' => [
                'resolvers' => [
                    'CmsDoctrine\Mapping\Translatable\TranslationInterface'
                        => 'CmsDoctrineORM\Mapping\Translatable\Translation',
                    'Gedmo\Translatable\Entity\Translation'
                        => 'CmsDoctrineORM\Mapping\Translatable\Translation',
                ],
            ],
        ],
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    'CmsDoctrine\Mapping\Embedded\OverrideSubscriber'
                        => 'CmsDoctrine\Mapping\Embedded\OverrideSubscriber',
                    'CmsDoctrine\Mapping\Relation\OverrideSubscriber'
                        => 'CmsDoctrine\Mapping\Relation\OverrideSubscriber',
                    'CmsDoctrine\Mapping\Metadatable\MetadatableSubscriber'
                        => 'CmsDoctrine\Mapping\Metadatable\MetadatableSubscriber',
                    'CmsDoctrine\Mapping\Hierarchy\HierarchySubscriber'
                        => 'CmsDoctrine\Mapping\Hierarchy\HierarchySubscriber',
                    'CmsDoctrine\Mapping\Sluggable\SluggableSubscriber'
                        => 'CmsDoctrine\Mapping\Sluggable\SluggableSubscriber',
                    'CmsDoctrine\Mapping\Translatable\TranslatableSubscriber'
                        => 'CmsDoctrine\Mapping\Translatable\TranslatableSubscriber',
                    'CmsDoctrine\Mapping\Dateable\TimestampableSubscriber'
                        => 'CmsDoctrine\Mapping\Dateable\TimestampableSubscriber',
                    'CmsDoctrine\Mapping\Blameable\BlameableSubscriber'
                        => 'CmsDoctrine\Mapping\Blameable\BlameableSubscriber',
                ],
            ],
        ],
        'formannotationbuilder' => [
            'orm_default' => [
                'cache' => 'array',
                'listeners' => [
                    'CmsDoctrine\Form\Annotation\ElementResolverListener'
                        => 'CmsDoctrine\Form\Annotation\ElementResolverListener',
                    'CmsDoctrineORM\Form\Annotation\ElementListener'
                        => 'CmsDoctrineORM\Form\Annotation\ElementListener',
                ],
            ],
        ],
        'initializers' => [
            'orm_default' => [
                'CmsCommon\Initializer\ServiceManagerInitializer'
                    => 'CmsCommon\Initializer\ServiceManagerInitializer',
            ],
        ],
    ],
    'doctrine_factories' => [
        'configuration' => 'CmsDoctrineORM\Factory\ConfigurationFactory',
        'discriminator_map' => 'CmsDoctrineORM\Factory\DiscriminatorMapFactory',
        'entitymanager' => 'CmsDoctrineORM\Factory\EntityManagerFactory',
        'formannotationbuilder' => 'CmsDoctrineORM\Factory\Form\AnnotationBuilderFactory',
        'initializers' => 'CmsDoctrineORM\Factory\InitializersFactory',
    ],
    'form_elements' => [
        'factories' => [
            'DoctrineModule\Form\Element\ObjectMultiCheckbox'
                => 'CmsDoctrineORM\Factory\Form\ObjectMultiCheckboxFactory',
            'DoctrineModule\Form\Element\ObjectRadio'
                => 'CmsDoctrineORM\Factory\Form\ObjectRadioFactory',
            'DoctrineModule\Form\Element\ObjectSelect'
                => 'CmsDoctrineORM\Factory\Form\ObjectSelectFactory',
        ],
        'abstract_factories' => [
            'CmsDoctrineORM\Form\Annotation\FormAbstractServiceFactory'
                => 'CmsDoctrineORM\Form\Annotation\FormAbstractServiceFactory',
        ],
    ],
    'hydrators' => [
        'aliases' => [
            'DoctrineModule\Stdlib\Hydrator\DoctrineObject'
                => 'CmsDoctrine\Stdlib\Hydrator\DoctrineObject',
        ],
        'factories' => [
            'CmsDoctrine\Stdlib\Hydrator\DoctrineObject'
                => 'CmsDoctrineORM\Factory\DoctrineObjectHydratorFactory'
        ],
    ],
    'mappers' => [
        'abstract_factories' => [
            'CmsDoctrineORM\Persistence\MapperAbstractServiceFactory'
                => 'CmsDoctrineORM\Persistence\MapperAbstractServiceFactory',
        ],
        'initializers' => [
            'CmsDoctrineORM\Initializer\TranslatableLocaleInitializer'
                => 'CmsDoctrineORM\Initializer\TranslatableLocaleInitializer',
        ],
    ],
    'service_manager' => [
        'aliases' => [
            'CmsDoctrineORM\Form\Annotation\AnnotationBuilder' => 'Doctrine\ORM\FormAnnotationBuilder',
        ],
        'factories' => [
            'Doctrine\ORM\FormAnnotationBuilder'
                => 'CmsDoctrineORM\Factory\Form\AnnotationBuilderAliasCompatFactory',
        ],
        'invokables' => [
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
