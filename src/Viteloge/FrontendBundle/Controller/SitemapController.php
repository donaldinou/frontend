<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Serializer\Serializer;
    use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
    use Symfony\Component\Serializer\Encoder\JsonEncoder;
    use Acreat\InseeBundle\Entity\InseeState;
    use Acreat\InseeBundle\Entity\InseeDepartment;
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
         *     name="viteloge_frontend_sitemap_index_format",
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
            $breadcrumbs->addItem($translated->trans('breadcrumb.sitemap', array(), 'breadcrumbs'));
            // --

            $this->elements = new \ArrayObject();
            $this->buildStates();
            $this->build();

            return array(
                'urls' => $this->elements
            );
        }

        /**
         * @Route(
         *     "/sitemap/state/{id}",
         *      defaults={
         *          "_format"="html"
         *      },
         *      name="viteloge_frontend_sitemap_state",
         *      options = {
         *          "i18n" = true
         *      }
         * )
         * @Route(
         *     "/sitemap/state/{id}.{_format}",
         *      requirements={
         *          "_format"="html|json"
         *      },
         *      defaults={
         *          "_format"="html"
         *      },
         *      name="viteloge_frontend_sitemap_state",
         *      options = {
         *          "i18n" = true
         *      }
         * )
         * @ParamConverter("inseeState", class="AcreatInseeBundle:InseeState", options={"id" = "id"})
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template()
         */
        public function stateAction(Request $request, InseeState $inseeState, $_format) {
            $translated = $this->get('translator');

            $section = new Element();
            $section->setName($inseeState->getName());
            //$section->setSection();
            $section->setDescription($translated->trans('sitemap.properties.for.name', array('%name%' => $inseeState->getFullName())));
            $section->setLoc(
                $this->get('router')->generate(
                    'viteloge_frontend_ad_search',
                    array(
                        'whereState' => array($inseeState->getId())
                    ),
                    true
                )
            );

            $this->elements = new \ArrayObject();
            $departments = $inseeState->getInseeDepartments();
            $this->appendDepartments($departments);

            return array(
                'section' => $section,
                'urls' => $this->elements
            );
        }

        /**
         * @Route(
         *     "/sitemap/department/{id}",
         *      defaults={
         *          "_format"="html"
         *      },
         *      name="viteloge_frontend_sitemap_department",
         *      options = {
         *          "i18n" = true
         *      }
         * )
         * @Route(
         *     "/sitemap/department/{id}.{_format}",
         *      requirements={
         *          "_format"="html|json"
         *      },
         *      defaults={
         *          "_format"="html"
         *      },
         *      name="viteloge_frontend_sitemap_department",
         *      options = {
         *          "i18n" = true
         *      }
         * )
         * @ParamConverter("inseeDepartment", class="AcreatInseeBundle:InseeDepartment", options={"id" = "id"})
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template()
         */
        public function departmentAction(Request $request, InseeDepartment $inseeDepartment, $_format) {
            $translated = $this->get('translator');

            $section = new Element();
            $section->setName($inseeDepartment->getName());
            //$section->setSection();
            $section->setDescription($translated->trans('sitemap.properties.for.name', array('%name%' => $inseeDepartment->getFullName())));
            $section->setLoc(
                $this->get('router')->generate(
                    'viteloge_frontend_ad_search',
                    array(
                        'whereDepartment' => array($inseeDepartment->getId())
                    ),
                    true
                )
            );

            $this->elements = new \ArrayObject();
            $cities = $inseeDepartment->getInseeCities();
            $this->appendCities($cities);

            return array(
                'section' => $section,
                'urls' => $this->elements
            );
        }

        /**
         *
         */
        public function build() {
            $translated = $this->get('translator');

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

        /**
         *
         */
        public function buildStates() {
            $translated = $this->get('translator');

            $repository = $this->getDoctrine()
                ->getRepository('AcreatInseeBundle:InseeState');
            $states = $repository->findBy(array(), array('name' => 'ASC'));
            $this->appendStates($states);

            return $this->elements;
        }

        /**
         *
         */
        public function buildDepartments() {
            $translated = $this->get('translator');

            $repository = $this->getDoctrine()
                ->getRepository('AcreatInseeBundle:InseeDepartment');
            $departments = $repository->findBy(array(), array('name' => 'ASC'));
            $this->appendDepartments();

            return $this->elements;
        }

        /**
         *
         */
        protected function appendStates($states) {
            $translated = $this->get('translator');

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
                $element->setChildrenLoc(
                    $this->get('router')->generate(
                        'viteloge_frontend_sitemap_state',
                        array(
                            'id' => $state->getId(),
                            '_format' => 'html'
                        ),
                        true
                    )
                );
                $this->elements->append($element);
            }
            return $this->elements;
        }

        /**
         *
         */
        protected function appendDepartments($departments) {
            $translated = $this->get('translator');

            foreach ($departments as $key => $department) {
                $element = new Element();
                $element->setName($department->getFullName());
                $element->setDescription($translated->trans('sitemap.properties.for.name', array('%name%' => $department->getFullName())));
                $element->setLoc(
                    $this->get('router')->generate(
                        'viteloge_frontend_ad_search',
                        array(
                            'whereState' => array($department->getId())
                        ),
                        true
                    )
                );
                $element->setChild(true);
                $element->setChildrenLoc(
                    $this->get('router')->generate(
                        'viteloge_frontend_sitemap_department',
                        array(
                            'id' => $department->getId(),
                            '_format' => 'html'
                        ),
                        true
                    )
                );
                $this->elements->append($element);
            }
            return $this->elements;
        }

        /**
         *
         */
        protected function appendCities($cities) {
            $translated = $this->get('translator');

            foreach ($cities as $key => $city) {
                $element = new Element();
                $element->setName($city->getFullName());
                $element->setDescription($translated->trans('sitemap.properties.for.name', array('%name%' => $city->getFullName())));
                $element->setLoc(
                    $this->get('router')->generate(
                        'viteloge_frontend_glossary_showcity',
                        array(
                            'name' => $city->getSlug(),
                            'id' => $city->getId()
                        ),
                        true
                    )
                );

                // if city has child
                /*$element->setChild(true);
                $element->setChildrenLoc(
                    $this->get('router')->generate(
                        'viteloge_frontend_sitemap_city',
                        array(
                            'id' => $city->getId(),
                            '_format' => 'html'
                        ),
                        true
                    )
                );*/
                // endif

                $this->elements->append($element);
            }
            return $this->elements;
        }

        /**
         * note : unused at this moment
         */
        public function buildSection($section) {
            $repository = $this->getDoctrine()
                ->getRepository('AcreatInseeBundle:InseeState');
        }

    }

}
