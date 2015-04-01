<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/CmsDoctrineORM for the canonical source repository
 * @copyright Copyright (c) 2006-2014 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Query;

use Doctrine\ORM\Query,
    Doctrine\ORM\QueryBuilder,
    CmsDoctrine\Mapping\Translatable\TranslatableSubscriber;

/**
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
trait TranslatableQueryProviderTrait
{
    /**
     * @var string Locale
     */
    protected $locale;

    /**
     * @param string $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getTranslatableLocale()
    {
        return $this->locale;
    }

    /**
     * Returns translated Doctrine query instance
     *
     * @param QueryBuilder $qb     A Doctrine query builder instance
     * @param string       $locale A locale name
     * @return Query
     */
    protected function getQuery(QueryBuilder $qb, $locale = null)
    {
        $query  = $qb->getQuery();
        $locale = $locale ?: $this->getTranslatableLocale();

        if ($locale) {
            // Use Translation Walker
            $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
                // Force the locale
                ->setHint(TranslatableSubscriber::HINT_TRANSLATABLE_LOCALE, $locale);
        }

        return $query;
    }
}
