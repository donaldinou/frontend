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
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new FOS\ElasticaBundle\FOSElasticaBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new WhiteOctober\BreadcrumbsBundle\WhiteOctoberBreadcrumbsBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Genemu\Bundle\FormBundle\GenemuFormBundle(), // used in Estimation
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(), // used in order to load only enabled
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            new EWZ\Bundle\RecaptchaBundle\EWZRecaptchaBundle(),
            new Presta\SitemapBundle\PrestaSitemapBundle(),
            new Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle(),
            new Acreat\CoreBundle\AcreatCoreBundle(),
            new Acreat\InseeBundle\AcreatInseeBundle(),
            new Viteloge\CoreBundle\VitelogeCoreBundle(),
            new Viteloge\FrontendBundle\VitelogeFrontendBundle(),
            new Viteloge\GlossaryBundle\VitelogeGlossaryBundle(),
            new Viteloge\EstimationBundle\VitelogeEstimationBundle(),
            new Viteloge\UserBundle\VitelogeUserBundle(),
            new Viteloge\MailBundle\VitelogeMailBundle(),
            new Viteloge\NewsBundle\VitelogeNewsBundle(),
            new Viteloge\OAuthBundle\VitelogeOAuthBundle(),
            new Viteloge\TwigBundle\VitelogeTwigBundle(),
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
