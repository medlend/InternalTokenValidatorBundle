<?php

namespace lendolsi\InternalTokenValidatorBundle\lib;

interface GeneratorInterface
{
    /**
     * @param string $time
     *
     * @return string
     */
    public function generate($time = 'now');

    /**
     * @return \DateTimeZone
     */
    public function getTimeZone();
}