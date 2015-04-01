<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/CmsDoctrineORM for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Translatable\MappedSuperclass;

use Zend\Form\Annotation as Form,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation,
    CmsCommon\Mapping\Common\ObjectableInterface;

/**
 * Abstract translation class
 *
 * @ORM\MappedSuperclass(repositoryClass="CmsDoctrineORM\Mapping\Translatable\Repository\TranslationRepository")
 */
abstract class AbstractTranslation extends AbstractPersonalTranslation implements ObjectableInterface
{
    /**
     * @var string
     *
     * @ORM\Column(type="string",length=10)
     * @Form\Exclude()
     */
    protected $locale;

    /**
     * @var string
     *
     * @ORM\Column(type="string",length=255,nullable=false)
     * @Form\Exclude()
     */
    protected $content;

    /**
     * Convenient constructor
     *
     * @param string $locale
     * @param string $field
     * @param string $content
     */
    public function __construct($locale, $field, $content)
    {
        $this->setLocale($locale);
        $this->setField($field);
        $this->setContent($content);
    }
}
