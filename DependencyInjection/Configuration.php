<?php

namespace lendolsi\InternalTokenValidatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('internal_token_validator');
        $rootNode
            ->isRequired()
            ->beforeNormalization()
                ->ifTrue(function($config) {
                    // $config contains the raw configuration values
                    return isset($config['salt_type'])
                        && $config['salt_type'] === 'remote'
                        && filter_var($config['salt'], FILTER_VALIDATE_URL) == false
                    ;
                })
                ->then(function($config) {
                    $config['salt'] = 'invalid';
                    return $config;
                })
            ->end()
            ->children()
                ->enumNode('salt_type')
                    ->values(['local', 'remote'])
                    ->isRequired()
                ->end()
                ->scalarNode('salt')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function ($value) {
                            return $value == 'invalid';
                        })
                        ->thenInvalid('the given salt is not a valid url')
                ->end()
            ->end();


        return $treeBuilder;
    }
}