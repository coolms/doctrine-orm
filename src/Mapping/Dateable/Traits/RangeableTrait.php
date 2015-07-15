<?php
/**
 * CoolMS2 Doctrine ORM Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/doctrine-orm for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsDoctrineORM\Mapping\Dateable\Traits;

use DateTime;

trait RangeableTrait
{
    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime",nullable=false)
     * @Form\Exclude()
     */
    protected $startDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime",nullable=false)
     * @Form\Exclude()
     */
    protected $endDate;

    /**
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $date
     */
    public function setStartDate(DateTime $date)
    {
        $this->startDate = $date;
    }

    /**
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $date
     */
    public function setEndDate(DateTime $date)
    {
        $this->endDate = $date;
    }
}
