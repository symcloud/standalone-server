<?php

namespace Symcloud\Component\Standalone\ContainerCompiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CommandsCompilerPass implements CompilerPassInterface
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
            $definition->addMethodCall('add', array(new Reference($id)));
            foreach ($tags as $attributes) {
                if (array_key_exists('default', $attributes) && $attributes['default']) {
                    $definition->addMethodCall(
                        'setDefaultCommand',
                        array($serviceDefinition->getArgument(0))
                    );
                }
            }
        }
    }
}
