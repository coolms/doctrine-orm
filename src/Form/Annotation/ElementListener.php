<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
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

class ElementListener extends AbstractListenerAggregate
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
        $this->listeners[] = $events->attach('configureElement', [$this, 'handleRequired']);
        $this->listeners[] = $events->attach('configureElement', [$this, 'handleAllowEmpty']);
    }

    /**
     * @param \Zend\EventManager\EventInterface $e
     */
    public function handleRequired($e)
    {
        $formSpec = $e->getParam('formSpec');
        if (!(isset($formSpec['object']) && $this->objectManager->getMetadataFactory()->hasMetadataFor($formSpec['object']))) {
            return;
        }

        $metadata = $this->objectManager->getClassMetadata($formSpec['object']);
        $fieldName = $e->getParam('elementSpec')['spec']['name'];

        if (!$metadata->hasField($fieldName)) {
            return;
        }

        $fieldMapping = $metadata->getFieldMapping($fieldName);

        $elementSpec = $e->getParam('elementSpec');
        if (!isset($elementSpec['attributes']['required'])) {
            $elementSpec['attributes']['required'] = !$fieldMapping['nullable'];
            $e->setParam('elementSpec', $elementSpec);
        }
    }

    /**
     * @param \Zend\EventManager\EventInterface $e
     */
    public function handleAllowEmpty($e)
    {
        $formSpec = $e->getParam('formSpec');
        if (!(isset($formSpec['object']) && $this->objectManager->getMetadataFactory()->hasMetadataFor($formSpec['object']))) {
            return;
        }

        $metadata = $this->objectManager->getClassMetadata($formSpec['object']);
        $fieldName = $e->getParam('elementSpec')['spec']['name'];

        if ($metadata->hasField($formSpec['name'] . '.' . $fieldName)) {
            $fieldName = $formSpec['name'] . '.' . $fieldName;
        } elseif (!$metadata->hasField($fieldName)) {
            return;
        }

        $fieldMapping = $metadata->getFieldMapping($fieldName);

        $inputSpec = $e->getParam('inputSpec');
        if (!isset($inputSpec['allow_empty'])) {
            $inputSpec['allow_empty'] = $fieldMapping['nullable'];
            $e->setParam('inputSpec', $inputSpec);
        }
    }
}
