<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Factory\Form;

use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory that provides the `Doctrine\ORM\FormAnnotationBuilder` alias
 * for `doctrine.formannotationbuilder.orm_default`
 */
class AnnotationBuilderAliasCompatFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return \CmsDoctrineORM\Form\Annotation\AnnotationBuilder
     *
     * @deprecated this method was introduced to allow aliasing
     *             of service `CmsDoctrineORM\Form\Annotation\AnnotationBuilder`
     *             from `doctrine.formannotationbuilder.orm_default`
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('doctrine.formannotationbuilder.orm_default');
    }
}
