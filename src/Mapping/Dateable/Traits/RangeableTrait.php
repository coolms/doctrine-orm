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
     * @return null|DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param string|int|null|DateTime $date
     */
    public function setStartDate($date)
    {
        $this->startDate = $this->normalizeDate($date);
    }

    /**
     * @return null|DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param string|int|null|DateTime $date
     */
    public function setEndDate($date)
    {
        $this->endDate = $this->normalizeDate($date);
    }

    /**
     * @param string|int|DateTime $date
     * @return DateTime
     */
    private function normalizeDate($date)
    {
        if (null === $date || $date instanceof DateTime) {
            return $date;
        }

        return new DateTime(is_int($date) ? "@$date" : $date);
    }
}
