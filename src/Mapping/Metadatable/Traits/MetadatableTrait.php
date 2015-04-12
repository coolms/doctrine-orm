<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Metadatable\Traits;

use Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\Collections\Collection,
    CmsDoctrine\Mapping\Metadatable\MetadataInterface;

/**
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait MetadatableTrait
{
    /**
     * @var MetadataInterface[]
     *
     * @Form\Exclude()
     */
    protected $metadata;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->metadata = new ArrayCollection();
    }

    /**
     * @param array|Traversable $metadata
     */
    public function setMetadata($metadata)
    {
        $this->clearMetadata();
        $this->addMetadata($metadata);
    }

    /**
     * @param array|Traversable|MetadataInterface $metadata
     */
    public function addMetadata($metadata)
    {
        if ($metadata instanceof MetadataInterface) {
            if (!$this->getMetadata()->contains($metadata)) {
                $this->getMetadata()->add($metadata);
                $metadata->setObject($this);
            }
        } elseif (is_array($metadata) || $metadata instanceof \Traversable) {
            foreach ($metadata as $meta) {
                $this->addMetadata($meta);
            }
        } else {
            throw new \InvalidArgumentException('Expected argument of type array or '
                . 'instance of Traversable or CmsDoctrine\Mapping\Metadatable\MetadataInterface, '
                . gettype($metadata) . ' given');
        }
    }

    /**
     * @param array|Traversable|MetadataInterface $metadata
     */
    public function removeMetadata($metadata)
    {
        if ($metadata instanceof MetadataInterface) {
            $this->getMetadata()->removeElement($metadata);
        } elseif (is_array($metadata) || $metadata instanceof \Traversable) {
            foreach ($metadata as $meta) {
                $this->removeMetadata($meta);
            }
        } else {
            throw new \InvalidArgumentException('Expected argument of type array or '
                . 'instance of Traversable or CmsDoctrine\Mapping\Metadatable\MetadataInterface, '
                . gettype($metadata) . ' given');
        }
    }

    /**
     * @return Collection
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Removes all metadata
     */
    public function clearMetadata()
    {
        $this->getMetadata()->clear();
    }
}
