<?php
    
    namespace NeoxNotify\NeoxNotifyBundle\DependencyInjection;
    
    use Symfony\Component\Config\Definition\Builder\TreeBuilder;
    use Symfony\Component\Config\Definition\ConfigurationInterface;
    
    class Configuration implements ConfigurationInterface
    {
        public function getConfigTreeBuilder(): TreeBuilder
        {
            $treeBuilder    = new TreeBuilder('neox_notify');
            $rootNode       = $treeBuilder->getRootNode();
            $rootNode
                ->children()
                    ->arrayNode('template')->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('include')->defaultValue("template/Partial/")->end()
                            ->scalarNode('emails')->defaultValue("template/Partial/Emails")->end()
                        ->end()
                    ->end() // template
                ->end();
            
            return $treeBuilder;
        }
    }