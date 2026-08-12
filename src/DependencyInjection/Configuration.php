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
                ->integerNode('max_multiplier')->defaultValue(2)->end()
            ->end();

        return $treeBuilder;
    }
}