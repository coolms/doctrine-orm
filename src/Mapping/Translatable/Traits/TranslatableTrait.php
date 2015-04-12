<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Translatable\Traits;

use Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\Collections\Collection,
    CmsDoctrineORM\Mapping\Translatable\MappedSuperclass\AbstractTranslation;

trait TranslatableTrait
{
    /**
     * @var AbstractTranslation[]
     *
     * @Form\Exclude()
     */
    protected $translations;

    /**
     * @var string Used locale to override Translation listener's locale this is not a mapped field of entity metadata, just a simple property
     *
     * @Gedmo\Locale
     * @Form\Type("Select")
     * @Form\Options({
     *      "label":"Language",
     *      "text_domain":"default",
     *      })
     * @Form\Flags({"priority":100})
     */
    protected $locale;

    /**
     * __construct
     *
     * Initializes translations
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * @param AbstractTranslation[] $translations
     */
    public function setTranslations($translations)
    {
        $this->clearTranslations();
        $this->addTranslations($translations);
    }

    /**
     * @param AbstractTranslation[] $translations
     */
    public function addTranslations($translations)
    {
        foreach ($translations as $translation) {
            $this->addTranslation($translation);
        }
    }

    /**
     * @param AbstractTranslation $translation
     */
    public function addTranslation(AbstractTranslation $translation)
    {
        if (!$this->getTranslations()->contains($translation)) {
            $this->getTranslations()->add($translation);
            $translation->setObject($this);
        }
    }

    /**
     * @param AbstractTranslation[] $translations
     */
    public function removeTranslations($translations)
    {
        foreach ($translations as $translation) {
            $this->removeTranslation($translation);
        }
    }

    /**
     * @param AbstractTranslation $translation
     */
    public function removeTranslation(AbstractTranslation $translation)
    {
        $this->getTranslations()->removeElement($translation);
    }

    /**
     * @return Collection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Removes all translations
     */
    public function clearTranslations()
    {
        $this->getTranslations()->clear();
    }

    /**
     * @param string|\CmsLocale\Mapping\LocaleInterface $locale
     */
    public function setTranslatableLocale($locale)
    {
        if ($locale instanceof \CmsLocale\Mapping\LocaleInterface) {
            $locale = $locale->getCanonicalName();
        }
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getTranslatableLocale()
    {
        return $this->locale;
    }
}
