<?php

namespace aintreallydown\DepositBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('deposit');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('property_class')
                    ->isRequired()
                    ->info('FQCN de l\'entité Property de l\'application hôte')
                ->end()
                ->arrayNode('methods')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('get_uid')->defaultValue('getUid')->end()
                        ->scalarNode('get_owner')->defaultValue('getOwner')->end()
                        ->scalarNode('get_state')->defaultValue('getState')->end()
                        ->scalarNode('set_state')->defaultValue('setState')->end()
                        ->scalarNode('get_rent')->defaultValue('getRent')->end()
                        ->scalarNode('get_charges')->defaultValue('getCharges')->end()
                        ->scalarNode('is_furnished')->defaultValue('isFurnished')->end()
                        ->scalarNode('get_extrafields')->defaultValue('getExtrafields')->end()
                        ->scalarNode('set_extrafields')->defaultValue('setExtrafields')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}