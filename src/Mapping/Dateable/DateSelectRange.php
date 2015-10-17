<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Dateable;

use DateTime,
    Zend\Form\Annotation as Form,
    Doctrine\ORM\Mapping as ORM,
    CmsCommon\Mapping\Dateable\RangeableInterface;

/**
 * @ORM\Embeddable
 * @Form\Name("dateRange")
 * @Form\Instance("CmsDoctrineORM\Mapping\Dateable\DateSelectRange")
 * @Form\Hydrator("DoctrineModule\Stdlib\Hydrator\DoctrineObject")
 * @Form\Type("DateSelectRange")
 * @Form\Options({
 *      "label":"Select date range",
 *      "text_domain":"default",
 *      "partial":"cms-common/date-select-range-fieldset"})
 */
class DateSelectRange implements RangeableInterface
{
    use Traits\RangeableTrait;

    /**
     * __construct
     *
     * @param string|int|null|DateTime $startDate
     * @param string|int|null|DateTime $endDate
     */
    public function __construct($startDate = null, $endDate = null)
    {
        $this->setStartDate($startDate);
        $this->setEndDate($endDate);
    }
}
