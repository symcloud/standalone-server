<?php

namespace Symcloud\Application\BlobStorage\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('blob_storage');
        $rootNode
            ->children()
                ->scalarNode('name')->cannotBeEmpty()->defaultValue('Acme Test')->end()
            ->end();

        return $treeBuilder;
    }
}
