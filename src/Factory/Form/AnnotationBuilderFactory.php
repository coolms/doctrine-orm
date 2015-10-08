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

use RuntimeException,
    Zend\EventManager\ListenerAggregateInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    DoctrineModule\Service\AbstractFactory,
    CmsCommon\Form\Options\FormAnnotationBuilder as AnnotationBuilderOptions,
    CmsDoctrineORM\Form\Annotation\AnnotationBuilder;

/**
 * Factory for {@see AnnotationBuilder}
 */
class AnnotationBuilderFactory extends AbstractFactory
{
    /**
     * {@inheritDoc}
     *
     * @throws RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $options \CmsDoctrineORM\Options\FormAnnotationBuilder */
        $options = $this->getOptions($serviceLocator, 'formannotationbuilder');

        /* @var $entityManager \Doctrine\ORM\EntityManager */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.' . $this->getName());

        $cache = $serviceLocator->has($options->getCache())
            ? $serviceLocator->get($options->getCache())
            : null;

        $builder = new AnnotationBuilder($entityManager, $cache);

        if ($serviceLocator->has('FormElementManager')) {
            $serviceLocator->get('FormElementManager')->injectFactory($builder);
        }

        foreach ($options->getAnnotations() as $annotation) {
            $builder->getAnnotationParser()->registerAnnotation($annotation);
        }

        $events = $builder->getEventManager();
        foreach ($options->getListeners() as $listener) {
            $listener = $serviceLocator->has($listener)
                ? $serviceLocator->get($listener)
                : new $listener($entityManager);

            if (!$listener instanceof ListenerAggregateInterface) {
                throw new RuntimeException(sprintf(
                    'Invalid event listener (%s) provided',
                    get_class($listener)
                ));
            }

            $events->attach($listener);
        }

        if (null !== $options->getPreserveDefinedOrder()) {
            $builder->setPreserveDefinedOrder($options->getPreserveDefinedOrder());
        }

        return $builder;
    }

    /**
     * {@inheritDoc}
     */
    public function getOptionsClass()
    {
        return AnnotationBuilderOptions::class;
    }
}
