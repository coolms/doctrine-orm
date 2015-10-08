<?php
/**
 * CoolMS2 Doctrine ORM module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Query;

use Doctrine\ORM\Query,
    Doctrine\ORM\QueryBuilder,
    Gedmo\Translatable\Query\TreeWalker\TranslationWalker,
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
        if (null === $this->locale) {
            return \Locale::getDefault();
        }

        return $this->locale;
    }

    /**
     * Returns translated Doctrine query instance
     *
     * @param QueryBuilder $qb     A Doctrine query builder instance
     * @param string       $locale A locale name
     * @return Query
     */
    public function getQuery(QueryBuilder $qb, $locale = null)
    {
        $query  = $qb->getQuery();
        $locale = $locale ?: $this->getTranslatableLocale();

        if ($locale) {
            // Use Translation Walker
            $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, TranslationWalker::class)
                // Force the locale
                ->setHint(TranslatableSubscriber::HINT_TRANSLATABLE_LOCALE, $locale);
        }

        return $query;
    }
}
