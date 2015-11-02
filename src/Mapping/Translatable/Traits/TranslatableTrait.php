<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Translatable\Traits;

use Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\Collections\Collection,
    CmsDoctrine\Mapping\Translatable\TranslationInterface;

trait TranslatableTrait
{
    /**
     * @var TranslationInterface[]
     *
     * @Form\Exclude()
     */
    protected $translations = [];

    /**
     * Used locale to override Translation listener's locale. 
     * This is not a mapped field of entity metadata, just a simple property
     *
     * @var string
     *
     * @ORM\Locale
     * @Form\Type("LocaleSelect")
     * @Form\Required(false)
     * @Form\Options({
     *      "label":"Language",
     *      "empty_option":"Select Language",
     *      "display_names":true,
     *      "text_domain":"default"})
     * @Form\Flags({"priority":950})
     */
    protected $translatableLocale;

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
     * @param TranslationInterface[] $translations
     * @return self
     */
    public function setTranslations($translations)
    {
        $this->clearTranslations();
        $this->addTranslations($translations);

        return $this;
    }

    /**
     * @param TranslationInterface[] $translations
     * @return self
     */
    public function addTranslations($translations)
    {
        foreach ($translations as $translation) {
            $this->addTranslation($translation);
        }

        return $this;
    }

    /**
     * @param TranslationInterface $translation
     * @return self
     */
    public function addTranslation(TranslationInterface $translation)
    {
        if (!$this->getTranslations()->contains($translation)) {
            $this->getTranslations()->add($translation);
            $translation->setObject($this);
        }

        return $this;
    }

    /**
     * @param TranslationInterface[] $translations
     * @return self
     */
    public function removeTranslations($translations)
    {
        foreach ($translations as $translation) {
            $this->removeTranslation($translation);
        }

        return $this;
    }

    /**
     * @param TranslationInterface $translation
     * @return self
     */
    public function removeTranslation(TranslationInterface $translation)
    {
        $this->getTranslations()->removeElement($translation);
        return $this;
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
     *
     * @return self
     */
    public function clearTranslations()
    {
        $this->getTranslations()->clear();
        return $this;
    }

    /**
     * @param string $locale
     * @return self
     */
    public function setTranslatableLocale($locale)
    {
        $this->translatableLocale = (string) $locale;
        return $this;
    }

    /**
     * @return string
     */
    public function getTranslatableLocale()
    {
        return $this->translatablelocale;
    }
}
