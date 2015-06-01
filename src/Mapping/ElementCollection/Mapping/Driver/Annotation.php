<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\ElementCollection\Mapping\Driver;

use Doctrine\ORM\Mapping\ClassMetadata,
    Gedmo\Mapping\Driver\AbstractAnnotationDriver,
    CmsDoctrineORM\Mapping\ElementCollection\ElementCollectionSubscriber;

class Annotation extends AbstractAnnotationDriver
{
    /**
     * {@inheritDoc}
     */
    public function readExtendedMetadata($meta, array &$config)
    {
        $class = $this->getMetaReflectionClass($meta);
        foreach ($class->getProperties() as $property) {
            if ($meta->isMappedSuperclass && !$property->isPrivate() ||
                isset($meta->associationMappings[$property->name]) ||
                $meta->isInheritedField($property->name) ||
                $meta->isInheritedAssociation($property->name) ||
                $property->getDeclaringClass()->getName() !== $meta->getName()
            ) {
                continue;
            }

            $elementCollection = $this->reader->getPropertyAnnotation(
                $property,
                ElementCollectionSubscriber::ELEMENT_COLLECTION_ANNOTATION
            );

            if (!empty($elementCollection->value)) {
                $meta->associationMappings[$property->name] = [
                    'fieldName'                 => $property->name,
                    'indexBy'                   => $elementCollection->indexBy,
                    'sourceEntity'              => $meta->name,
                    'targetEntity'              => $elementCollection->targetEntity,
                    'type'                      => ClassMetadata::TO_MANY,
                    'fetch'                     => $elementCollection->fetch,
                    'inversedBy'                => null,
                    'mappedBy'                  => null,
                    'isOwningSide'              => false,
                    'cascade'                   => [],
                    'isCascadeDetach'           => false,
                    'isCascadeMerge'            => false,
                    'isCascadePersist'          => false,
                    'isCascadeRefresh'          => false,
                    'isCascadeRemove'           => false,
                    'isOnDeleteCascade'         => false,
                    'orphanRemoval'             => false,
                    'joinColumnFieldNames'      => [],
                    'targetToSourceKeyColumns'  => [],
                    'sourceToTargetKeyColumns'  => [],
                ];
            }
        }
    }
}
