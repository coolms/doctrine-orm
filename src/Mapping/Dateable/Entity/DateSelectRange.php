<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Dateable\Entity;

use DateTime,
    Zend\Form\Annotation as Form,
    Doctrine\ORM\Mapping as ORM,
    CmsCommon\Mapping\Dateable\RangeableInterface,
    CmsDoctrineORM\Mapping\Dateable\Traits\RangeableTrait;

/**
 * @ORM\Embeddable
 * @Form\Name("dateRange")
 * @Form\Instance("CmsDoctrineORM\Mapping\Dateable\Entity\DateSelectRange")
 * @Form\Hydrator("DoctrineModule\Stdlib\Hydrator\DoctrineObject")
 * @Form\Type("DateSelectRange")
 * @Form\Options({
 *      "create_empty_option":true,
 *      "label":"Select date range",
 *      })
 */
class DateSelectRange implements RangeableInterface
{
    use RangeableTrait;

    /**
     * __construct
     *
     * @param string|int|DateTime $startDate
     * @param string|int|DateTime $endDate
     */
    public function __construct($startDate = null, $endDate = null)
    {
        $this->setStartDate(new DateTime($startDate));
        $this->setEndDate(new DateTime($endDate));
    }
}
