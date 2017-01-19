<?php

namespace lendolsi\InternalTokenValidatorBundle\lib;

/**
 * Class Validator
 */
class Validator implements ValidatorInterface
{
    /**
     * @var GeneratorInterface
     */
    private $generator;

    /**
     * Validator constructor.
     *
     * @param GeneratorInterface $generator
     */
    public function __construct(GeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @param string $token
     * @param string $date
     *
     * @return bool
     */
    public function isValid($token, $date = 'now')
    {
        return $token == $this->generate($date);
    }

    /**
     * @param string $date
     *
     * @return string
     */
    protected function generate($date)
    {
        return $this->generator->generate(
            $this->getDateTime($date)
        );
    }

    /**
     * @param string $date
     *
     * @return \DateTime
     */
    protected function getDateTime($date)
    {
        if($date instanceof \DateTime)
            return $date;

        return new \DateTime($date, $this->getTimeZone());
    }

    /**
     * @return \DateTimeZone
     */
    protected function getTimeZone()
    {
        return $this->generator->getTimeZone();
    }
}