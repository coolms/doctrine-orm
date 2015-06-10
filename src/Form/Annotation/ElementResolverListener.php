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

use Zend\EventManager\AbstractListenerAggregate,
    Zend\EventManager\EventManagerInterface,
    Zend\Form\Annotation\ComposedObject,
    Doctrine\Common\Persistence\ObjectManager,
    DoctrineModule\Form\Element\ObjectSelect,
    DoctrineModule\Persistence\ProvidesObjectManager;

class ElementResolverListener extends AbstractListenerAggregate
{
    use ProvidesObjectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('configureElement', [$this, 'resolveComposedTargetObject'], PHP_INT_MAX);
        $this->listeners[] = $events->attach('configureElement', [$this, 'resolveObjectSelectTargetClass']);
    }

    /**
     * ComposedObject target object resolver
     *
     * Resolves the interface (specification) into entity object class
     *
     * @param \Zend\EventManager\EventInterface $e
     */
    public function resolveComposedTargetObject($e)
    {
        $annotation = $e->getParam('annotation');
        if (!$annotation instanceof ComposedObject) {
            return;
        }

        $formSpec     = $e->getParam('formSpec');
        $metadata     = $this->objectManager->getClassMetadata($formSpec['object']);
        $associations = $metadata->getAssociationMappings();
        $assocName    = $e->getParam('elementSpec')['spec']['name'];

        if (isset($associations[$assocName]['targetEntity'])) {
            $e->setParam('annotation', new ComposedObject([
                'value' => [
                    'target_object' => $associations[$assocName]['targetEntity'],
                    'is_collection' => $annotation->isCollection(),
                    'options'       => $annotation->getOptions(),
                ],
            ]));
        }
    }

    /**
     * ObjectSelect target class resolver
     *
     * Resolves the interface (specification) into entity object class
     *
     * @param \Zend\EventManager\EventInterface $e
     */
    public function resolveObjectSelectTargetClass($e)
    {
        $elementSpec = $e->getParam('elementSpec');
        if (!isset($elementSpec['spec']['type'])) {
            return;
        }

        $type = $elementSpec['spec']['type'];
        if (strtolower($type) !== 'objectselect' && !$type instanceof ObjectSelect) {
            return;
        }

        if (isset($elementSpec['spec']['options']['target_class']) &&
            class_exists($elementSpec['spec']['options']['target_class'])
        ) {
            return;
        }

        $formSpec     = $e->getParam('formSpec');
        $metadata     = $this->objectManager->getClassMetadata($formSpec['object']);
        $associations = $metadata->getAssociationMappings();
        $assocName    = $elementSpec['spec']['name'];

        if (isset($associations[$assocName]['targetEntity'])) {
            $elementSpec['spec']['options']['target_class'] = $associations[$assocName]['targetEntity'];
        }
    }
}
