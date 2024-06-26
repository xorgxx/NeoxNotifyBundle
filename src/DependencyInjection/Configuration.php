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
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('template')->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('include')->defaultValue("Partial\Emails\Include")->end()
                            ->scalarNode('emails')->defaultValue("Partial\Emails")->end()
                        ->end()
                    ->end() // template
                    ->arrayNode('service')
                        ->children()
                            ->arrayNode('channels')
                                ->scalarPrototype()->defaultValue(null)->end()
                            ->end()
                            ->scalarNode('subject')->defaultValue("subject")->end()
                            ->scalarNode('template')->defaultValue("default")->end()
                            ->scalarNode('content')->defaultValue("....")->end()
                        ->end()
                    ->end()
                 
                    ->booleanNode('save_notify')->defaultTrue()->end()
                ->end();
            
            return $treeBuilder;
        }
    }