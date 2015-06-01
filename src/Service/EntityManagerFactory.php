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

use Doctrine\Common\Collections\AbstractLazyCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\LazyCriteriaCollection;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

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

        @class_alias('Doctrine\ORM\PersistentCollection', 'CmsDoctrineORM\Persistence\PersistentCollection', false);
        //class_alias('PColl', 'PersistentCollection');

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

    protected function patchPersistentCollection()
    {
        function matching(Criteria $criteria)
        {
            if ($this->isDirty) {
                $this->initialize();
            }

            if ($this->initialized) {
                return $this->collection->matching($criteria);
            }

            if ($this->association['type'] === ClassMetadata::MANY_TO_MANY) {
                $persister = $this->em->getUnitOfWork()->getCollectionPersister($this->association);

                return new ArrayCollection($persister->loadCriteria($this, $criteria));
            }

            $expression = $criteria->getWhereExpression();
            if ($this->association['mappedBy']) {
                $builder         = Criteria::expr();
                $ownerExpression = $builder->eq($this->backRefFieldName, $this->owner);
                $expression      = $expression ? $builder->andX($expression, $ownerExpression) : $ownerExpression;
            }

            $criteria = clone $criteria;
            $criteria->where($expression);

            $persister = $this->em->getUnitOfWork()->getEntityPersister($this->association['targetEntity']);

            return ($this->association['fetch'] === ClassMetadataInfo::FETCH_EXTRA_LAZY)
                ? new LazyCriteriaCollection($persister, $criteria)
                : new ArrayCollection($persister->loadCriteria($criteria));
        }
    }
}
