<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\ElementCollection\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class ElementCollection extends Annotation
{
    /**
     * The fetching strategy to use for the association.
     *
     * @var string
     *
     * @Enum({"LAZY", "EAGER", "EXTRA_LAZY"})
     */
    public $fetch = 'EXTRA_LAZY';

    /**
     * @var string
     */
    public $indexBy;

    /**
     * {@inheritDoc}
     */
    public function __get($name)
    {
        if ($name === 'targetEntity') {
            return $this->value;
        }

        return parent::__get($name);
    }

    /**
     * {@inheritDoc}
     */
    public function __set($name, $value)
    {
        if ($name === 'targetEntity') {
            $this->value = $value;
            return;
        }

        parent::__set($name, $value);
    }
}
