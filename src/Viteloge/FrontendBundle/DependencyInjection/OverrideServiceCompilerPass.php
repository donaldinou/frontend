<?php

namespace Viteloge\FrontendBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('presta_sitemap.dumper');
        $definition->setClass('Viteloge\FrontendBundle\Services\Dumper');
    }
}

