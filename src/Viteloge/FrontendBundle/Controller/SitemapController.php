<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Serializer\Serializer;
    use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
    use Symfony\Component\Serializer\Encoder\JsonEncoder;
    use Viteloge\CoreBundle\Component\Enum\TransactionEnum;
    use Viteloge\CoreBundle\SearchEntity\Ad as AdSearch;
    use Viteloge\FrontendBundle\Component\Sitemap\Element;

    /**
     * @Route("/")
     */
    class SitemapController extends Controller {

        protected $elements;

        /**
         * @Route(
         *     "/sitemap",
         *      defaults={
         *          "_format"="html"
         *      },
         *     name="viteloge_frontend_sitemap_index",
         *     options = {
         *         "i18n" = true,
         *         "vl_sitemap" = true
         *     }
         * )
         * @Route(
         *     "/sitemap.{_format}",
         *      requirements={
         *          "_format"="html|json"
         *      },
         *      defaults={
         *          "_format"="html"
         *      },
         *     name="viteloge_frontend_sitemap_index",
         *     options = {
         *         "i18n" = true
         *     }
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template()
         */
        public function indexAction( Request $request, $_format ) {
            $translated = $this->get('translator');

            // Breadcrumbs
            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem(
                $translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->get('router')->generate('viteloge_frontend_homepage')
            );
            $breadcrumbs->addItem('Plan du site');
            // --

            /*$section = $this->get('presta_sitemap.generator')->fetch('default');
            $sxe = new \SimpleXMLElement($section->toXml());
            $urls = (isset($sxe->url)) ? $sxe->url : array();*/
            $this->build();
            $this->buildStates();

            return array(
                'urls' => $this->elements
            );
        }

        /**
         * @Route(
         *     "/sitemap/section/{id}",
         *      defaults={
         *          "_format"="html"
         *      },
         *     name="viteloge_frontend_sitemap_section",
         *     options = {
         *         "i18n" = true
         *     }
         * )
         * @Route(
         *     "/sitemap/section/{id}.{_format}",
         *      requirements={
         *          "_format"="html|json"
         *      },
         *      defaults={
         *          "_format"="html"
         *      },
         *     name="viteloge_frontend_sitemap_section",
         *     options = {
         *         "i18n" = true
         *     }
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template()
         */
        public function sectionAction(Request $request, $_format) {
            $translated = $this->get('translator');

            $this->elements = new \ArrayObject();
            $element = new Element();
            $element->setName($translated->trans('test'));
            //$element->setSection($section);
            $element->setDescription($translated->trans('testttt'));
            $element->setLoc('http://test.com');
            $this->elements->append($element);

            return array(
                'urls' => $this->elements
            );
        }

        public function build() {
            $translated = $this->get('translator');

            $this->elements = new \ArrayObject();
            $collection = $this->get('router')->getOriginalRouteCollection();
            foreach ($collection->all() as $name => $route) {
                $option = $route->getOption('vl_sitemap');
                if ($option) {
                    $title = (isset($option['title'])) ? $option['title'] : $name;
                    $section = (isset($option['section'])) ? $option['section'] : 'default';
                    $description = (isset($option['description'])) ? $option['description'] : '';
                    $loc = $this->get('router')->generate($name, array(), true);
                    $element = new Element();
                    $element->setName($translated->trans($title));
                    $element->setSection($section);
                    $element->setDescription($translated->trans($description));
                    $element->setLoc($loc);
                    $this->elements->append($element);
                }
            }
            return $this->elements;
        }

        public function buildStates() {
            $translated = $this->get('translator');

            $repository = $this->getDoctrine()
                ->getRepository('AcreatInseeBundle:InseeState');
            $states = $repository->findAll();
            foreach ($states as $key => $state) {
                $element = new Element();
                $element->setName($state->getFullName());
                $element->setDescription($translated->trans('sitemap.properties.for.name', array('%name%' => $state->getFullName())));
                $element->setLoc(
                    $this->get('router')->generate(
                        'viteloge_frontend_ad_search',
                        array(
                            'whereState' => array($state->getId())
                        ),
                        true
                    )
                );
                $element->setChild(true);
                $this->elements->append($element);
            }
        }

        /**
         *
         */
        public function buildSection($section) {
            $repository = $this->getDoctrine()
                ->getRepository('AcreatInseeBundle:InseeState');
        }

    }

}
