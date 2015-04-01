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
    Doctrine\ORM\QueryBuilder;

/**
 * Translatable Query interface
 *
 * This is translatable repository that offers methods to retrieve results with translations
 */
interface TranslatableQueryProviderInterface
{
    /**
     * @param string $locale
     */
    public function setTranslatableLocale($locale);

    /**
     * @return string
     */
    public function getTranslatableLocale();

    /**
     * @param QueryBuilder $qb
     * @param string $locale
     * @return Query
     */
    public function getQuery(QueryBuilder $qb, $locale);
}
