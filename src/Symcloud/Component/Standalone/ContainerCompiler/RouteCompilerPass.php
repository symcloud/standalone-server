<?php

namespace Symcloud\Component\Standalone\ContainerCompiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RouteCompilerPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $applicationId;

    /**
     * @var string
     */
    private $tagName;

    /**
     * CommandsCompilerPass constructor.
     * @param string $applicationId
     * @param string $tagName
     */
    public function __construct($applicationId, $tagName)
    {
        $this->applicationId = $applicationId;
        $this->tagName = $tagName;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->applicationId)) {
            return;
        }

        $definition = $container->getDefinition($this->applicationId);
        $taggedServices = $container->findTaggedServiceIds($this->tagName);
        foreach ($taggedServices as $id => $tags) {
            $serviceDefinition = $container->getDefinition($id);
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                    $attributes['method'],
                    array(
                        $attributes['pattern'],
                        $serviceDefinition->getClass() . '::' . $attributes['action']
                    )
                );
            }
        }
    }
}

