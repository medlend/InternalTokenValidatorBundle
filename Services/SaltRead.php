<?php

namespace lendolsi\InternalTokenValidatorBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

class SaltRead implements SaltReadInterface
{
    /*
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /*
     * @var array
     */
    protected $saltConfigs;

    public function __construct(ContainerInterface $container)
    {
        $this->container   = $container;
        //get bundle configs
        $this->saltConfigs = $this->container->getParameter("internal_token_validator");
    }

    /**
     *
     * @return string $salt
     */
    public function getSalt()
    {
        //get the salt type ('remote' or 'local')
        if ($this->isRemoteSalt()) {
            //test if the remote file exists and it contain a string
            $aData = $this->isRemoteSaltExists();
            if ($aData['status']) {
                return $aData['salt'];
            } else {
                return false;
            }
        }
        return $this->saltConfigs['salt'];
    }

    /**
     *
     * @return boolean
     */
    public function isRemoteSalt()
    {
        //get the salt type ('remote' or 'local')
        if ($this->saltConfigs['salt_type'] == 'remote') {
            return true;
        }
        return false;
    }

    /**
     *
     * @return boolean
     */
    public function isRemoteSaltExists()
    {
        //test if the remote file exists
        $file_headers = @get_headers($this->saltConfigs['salt']);
        if ((!$file_headers) || ($file_headers[0] == 'HTTP/1.1 404 Not Found')) {
            return [
                'status' => false,
                'salt' => null
            ];
        } else {
            //get salt from remote file
            $sSalt = file_get_contents($this->saltConfigs['salt']);
            //test if the remote file contain a string
            if (!(is_string($sSalt))) {
                return [
                    'status' => false,
                    'salt' => null
                ];
            }
        }
        return [
            'status' => true,
            'salt' => $sSalt
        ];
    }
}