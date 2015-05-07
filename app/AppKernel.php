<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Sonata\SeoBundle\SonataSeoBundle(),
            //new Symfony\Cmf\Bundle\SeoBundle\CmfSeoBundle(),
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new WhiteOctober\BreadcrumbsBundle\WhiteOctoberBreadcrumbsBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new FOS\ElasticaBundle\FOSElasticaBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Acreat\CoreBundle\AcreatCoreBundle(),
            new Acreat\InseeBundle\AcreatInseeBundle(),
            new Viteloge\CoreBundle\VitelogeCoreBundle(),
            new Viteloge\FrontendBundle\VitelogeFrontendBundle(),
            new Viteloge\GlossaryBundle\VitelogeGlossaryBundle(),
            new Viteloge\EstimationBundle\VitelogeEstimationBundle(),
            new Viteloge\UserBundle\VitelogeUserBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
