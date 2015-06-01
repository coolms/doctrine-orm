<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Service;

use Zend\ServiceManager\ServiceLocatorInterface,
    DoctrineORMModule\Service\EntityManagerFactory as BaseEntityManagerFactory,
    CmsDoctrineORM\UnitOfWork;

class EntityManagerFactory extends BaseEntityManagerFactory
{
    /**
     * {@inheritDoc}
     *
     * @return DoctrineEntityManager
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        /* @var $options \CmsDoctrineORM\Options\EntityManager */
        $options = $this->getOptions($sl, 'entitymanager');

        // initializing the discriminator and relation map
        // @todo should actually attach it to a fetched event manager here, and not
        //       rely on its factory code
        $sl->get($options->getDiscriminatorMap());

        $em = parent::createService($sl);

        // Injecting overridden UnitOfWork into EntityManager
        $refl = new \ReflectionClass(get_class($em));
        $prop = $refl->getProperty('unitOfWork');
        $prop->setAccessible(true);
        $prop->setValue($em, new UnitOfWork($em));

        return $em;
    }

    /**
     * {@inheritDoc}
     */
    public function getOptionsClass()
    {
        return 'CmsDoctrineORM\\Options\\EntityManager';
    }
}
