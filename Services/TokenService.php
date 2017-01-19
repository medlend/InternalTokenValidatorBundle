<?php

namespace lendolsi\InternalTokenValidatorBundle\Services;

use lendolsi\InternalTokenValidatorBundle\lib\Generator;
use lendolsi\InternalTokenValidatorBundle\lib\Validator;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TokenService
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var SaltReadInterface
     */
    protected $saltRead;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->saltRead  = new SaltRead($container);
    }

    /**
     *
     * @param \DateTime $dDate
     * @return array
     */
    public function generateToken($dDate = null)
    {
        $dDate = $dDate ? : new \DateTime('now');

        //get the token Generator
        $oGenerator = $this->getGenerator();
        return [
            //generate the token
            "lm_token" => $oGenerator->generate($dDate),
            "lm_token_date" => $dDate,
        ];
    }

    /**
     *
     * @param string $sToken
     * @param \DateTime $sTokenDate
     * @return boolean
     */
    public function validateToken($sToken, $sTokenDate)
    {
        // Validate any token when used dev environment
        if($this->isDevEnvironment()) {
            return true;
        }

        //get the token Generator
        $oGenerator = $this->getGenerator();

        //get the token Validator
        $oValidator = new Validator($oGenerator);

        //validate the given token
        if ($oValidator->isValid($sToken, $sTokenDate)) {
            return true;
        }
        return false;
    }

    /**
     *
     */
    public function getSaltRead()
    {
        return $this->saltRead;
    }

    /**
     *
     * @param $oContainer
     * @return \lendolsi\InternalTokenValidatorBundle\Services\TokenService
     */
    public function setSaltRead($oContainer)
    {
        $this->saltRead = new SaltRead($oContainer);

        return $this;
    }

    /**
     *
     * @return Generator
     */
    public function getGenerator()
    {
        //get the token Generator
        return new Generator($this->getSaltRead()->getSalt());
    }

    /**
     * Check the kernel environment
     *
     * @return bool
     */
    protected function isDevEnvironment()
    {
        return $this->container->has('kernel')
            && 'dev' == $this->container->get('kernel')->getEnvironment();
    }
}