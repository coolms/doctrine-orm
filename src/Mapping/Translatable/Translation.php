<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Translatable;

use Doctrine\ORM\Mapping as ORM,
    Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation,
    CmsDoctrine\Mapping\Translatable\TranslationInterface,
    CmsCommon\Mapping\Common\IdentifiableInterface;

/**
 * Common Translation Entity
 *
 * @ORM\Table(name="translations",
 *      indexes={@ORM\Index(name="translations_lookup_idx",
 *          columns={"locale","object_class","foreign_key"})},
 *      uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx",
 *          columns={"locale","object_class","field","foreign_key"})})
 * @ORM\Entity(repositoryClass="CmsDoctrineORM\Mapping\Translatable\Repository\TranslationRepository")
 */
class Translation extends AbstractTranslation implements TranslationInterface
{
    /**
     * {@inheritDoc}
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * {@inheritDoc}
     */
    public function setObject($object)
    {
        if ($object instanceof IdentifiableInterface) {
            $this->setForeignKey('id');
        }

        $this->setObjectClass(get_class($object));
    }

    /**
     * {@inheritDoc}
     */
    public function getObject()
    {
        return;
    }
}
