<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Form\Annotation;

use Zend\Cache\Storage\StorageInterface,
    Doctrine\Common\Persistence\ObjectManager,
    DoctrineORMModule\Form\Annotation\AnnotationBuilder as DoctrineAnnotationBuilder,
    CmsCommon\Cache\StorageProviderInterface,
    CmsCommon\Form\Annotation\AnnotationBuilderCacheTrait;

class AnnotationBuilder extends DoctrineAnnotationBuilder implements StorageProviderInterface
{
    use AnnotationBuilderCacheTrait;

    /**
     * {@inheritDoc}
     *
     * @param StorageInterface $cacheStorage
     */
    public function __construct(ObjectManager $objectManager, StorageInterface $cacheStorage = null)
    {
        parent::__construct($objectManager);
        if (null !== $cacheStorage) {
            $this->setCacheStorage($cacheStorage);
        }
    }
}
