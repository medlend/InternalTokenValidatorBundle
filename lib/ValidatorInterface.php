<?php
/**
 * Date: 04/07/2016
 * Time: 16:59
 */

namespace lendolsi\InternalTokenValidatorBundle\lib;


interface ValidatorInterface
{
    /**
     * @param string $token
     * @param string $date
     *
     * @return bool
     */
    public function isValid($token, $date = 'now');
}