<?php

namespace Viteloge\FrontendBundle\EventListener {

    use Symfony\Component\Routing\Route;
    use Symfony\Component\Routing\RouterInterface;
    use Presta\SitemapBundle\Service\SitemapListenerInterface;
    use Presta\SitemapBundle\Event\SitemapPopulateEvent;
    use Presta\SitemapBundle\EventListener\RouteAnnotationEventListener;
    use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

    class SitemapListener extends RouteAnnotationEventListener {

        /**
         *
         */
        private $router;

        /**
         *
         */
        public function __construct(RouterInterface $router) {
            $this->router = $router;
        }

        /**
         * Should check $event->getSection() and then populate the sitemap
         * using $event->getGenerator()->addUrl(\Presta\SitemapBundle\Sitemap\Url\Url $url, $section)
         * if $event->getSection() is null or matches the listener's section
         *
         * @param SitemapPopulateEvent $event
         *
         * @throws \InvalidArgumentException
         * @return void
         */
        public function populateSitemap(SitemapPopulateEvent $event)
        {
            $section = $event->getSection();
            if (is_null($section) || $section == 'default') {
                $this->addUrlsFromRoutes($event);
            }
        }

        /**
         * @param  SitemapPopulateEvent      $event
         * @throws \InvalidArgumentException
         */
        private function addUrlsFromRoutes(SitemapPopulateEvent $event)
        {
            $collection = $this->router->getOriginalRouteCollection();
            foreach ($collection->all() as $name => $route) {
                $options = $this->getOptions($name, $route);
                if ($options) {
                    $event->getGenerator()->addUrl(
                        $this->getUrlConcrete($name, $options),
                        $event->getSection() ? $event->getSection() : 'default'
                    );
                }
            }
        }

        /**
         * @param $name
         * @param  Route $route
         * @throws \InvalidArgumentException
         * @return array
         */
        public function getOptions($name, Route $route)
        {
            $option = $route->getOption('vl_sitemap');
            if ($option === null) {
                return null;
            }
            if (!filter_var($option, FILTER_VALIDATE_BOOLEAN) && !is_array($option)) {
                throw new \InvalidArgumentException('the sitemap option must be "true" or an array of parameters');
            }
            $options = array(
                'priority' => 1,
                'changefreq' => UrlConcrete::CHANGEFREQ_DAILY,
                'lastmod' => new \DateTime()
            );
            if (is_array($option)) {
                if (isset($option['lastmod'])) {
                    try {
                        $lastmod = new \DateTime($option['lastmod']);
                        $option['lastmod'] = $lastmod;
                    } catch (\Exception $e) {
                        throw new \InvalidArgumentException(
                            sprintf(
                                'The route %s has an invalid value "%s" specified for the "lastmod" option',
                                $name,
                                $option['lastmod']
                            )
                        );
                    }
                }
                $options = array_merge($options, $option);
            }
            return $options;
        }

        /**
         * @param $name
         * @param $options
         * @return UrlConcrete
         * @throws \InvalidArgumentException
         */
        private function getUrlConcrete($name, $options)
        {
            try {
                $url = new UrlConcrete(
                    $this->getRouteUri($name),
                    $options['lastmod'],
                    $options['changefreq'],
                    $options['priority']
                );
                return $url;
            } catch (\Exception $e) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Invalid argument for route "%s": %s',
                        $name,
                        $e->getMessage()
                    )
                );
            }
        }

        /**
         * @param $name
         * @return string
         * @throws \InvalidArgumentException
         */
        private function getRouteUri($name) {
            // does the route need parameters? if so, we can't add it
            try {
                return $this->router->generate($name, array(), true);
            } catch (MissingMandatoryParametersException $e) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'The route "%s" cannot have the sitemap option because it requires parameters',
                        $name
                    )
                );
            }
        }
    }

}
