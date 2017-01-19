<?php

namespace lendolsi\InternalTokenValidatorBundle\lib;

/**
 * Class Generator
 *
 */
class Generator implements GeneratorInterface
{
    /**
     * @const Default Timezone
     */
    const TIMEZONE = 'Europe/Paris';

    /**
     * @const granules minutes
     */
    const MINUTE_GRANULARITY = 5;

    /**
     * The common salt for the base of token
     *
     * @var string
     */
    private $salt;

    /**
     * Generator constructor.
     *
     * @param string $salt
     */
    public function __construct($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Generate a new token
     *
     * @param string $time The datetime or string represente time
     *
     * @return string
     */
    public function generate($time = 'now')
    {
        return sha1(
            $this->getHash($time)
        );
    }

    /**
     * @return \DateTimeZone
     */
    public function getTimeZone()
    {
        return new \DateTimeZone(static::TIMEZONE);
    }

    /**
     * @param \DateTime|string $time
     *
     * @return string
     */
    protected function getHash($time)
    {
        $dateTime = $this->getDateTime($time);

        return sprintf(
            '%s%s%s',
            $this->salt,
            $this->getDateKey($dateTime),
            $this->getMinuteKey($dateTime)
        );
    }

    /**
     * @param \DateTime|string $time
     *
     * @return \DateTime
     */
    protected function getDateTime($time)
    {
        if($time instanceof \DateTime)
            return $time;

        return new \DateTime($time, $this->getTimeZone());
    }


    /**
     * @param \DateTime $date
     *
     * @return int
     */
    protected function getMinuteKey(\DateTime $date)
    {
        $minutes = (int)$date->format('i');
        return $minutes - ($minutes % static::MINUTE_GRANULARITY);
    }

    /**
     * @param \DateTime $date
     *
     * @return string
     */
    protected function getDateKey(\DateTime $date)
    {
        return $date->format('YWdH');
    }
}