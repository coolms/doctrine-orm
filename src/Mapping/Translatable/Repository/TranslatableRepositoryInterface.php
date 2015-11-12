<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Translatable\Repository;

use Doctrine\ORM\AbstractQuery,
    Doctrine\ORM\QueryBuilder;

/**
 * Translatable Repository interface
 *
 * This is translatable repository that offers methods to retrieve results with translations
 */
interface TranslatableRepositoryInterface
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
     * @return AbstractQuery
     */
    public function getTranslatableQuery(QueryBuilder $qb, $locale);
}
