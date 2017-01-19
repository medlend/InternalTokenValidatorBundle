<?php

namespace lendolsi\InternalTokenValidatorBundle\Services;

interface SaltReadInterface
{

    /**
     * Check if the salt in configuration file is URL
     *
     * @return bool
     */
    public function isRemoteSalt();

    /**
     * Check if the salt in configuration is an accessible URL and if it contain a string
     *
     * @return bool
     */
    public function isRemoteSaltExists();

    /**
     * Retrieve the real salt, after download if on remote or the content on configuration file
     *
     * @return string
     */
    public function getSalt();
}