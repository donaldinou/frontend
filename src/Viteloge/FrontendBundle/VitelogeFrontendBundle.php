<?php

namespace Viteloge\FrontendBundle {

    use Symfony\Component\HttpKernel\Bundle\Bundle;
    use Symfony\Component\DependencyInjection\ContainerBuilder;
    use Viteloge\FrontendBundle\DependencyInjection\OverrideServiceCompilerPass;

    class VitelogeFrontendBundle extends Bundle
    {

    	public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideServiceCompilerPass());
    }

    }

}

