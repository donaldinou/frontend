<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use GeoIp2\Database\Reader;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Viteloge\CoreBundle\Entity\Ad;
    use Viteloge\CoreBundle\Entity\UserSearch;

    use Symfony\Component\Serializer\Serializer;
    use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
    use Symfony\Component\Serializer\Encoder\JsonEncoder;

    use Viteloge\CoreBundle\Component\DBAL\EnumTransactionType;
    use Viteloge\CoreBundle\SearchEntity\Ad as AdSearch;

    /**
     * @Route("/ad")
     */
    class AdController extends Controller {

        /**
         *
         */
        protected $form;

        /**
         *
         */
        protected function findFromRequest(Request $request, $limit=null) {
            if ($limit === null) {
                $limit = 1000000;
            }
            $elasticaManager = $this->container->get('fos_elastica.manager');
            $repository = $elasticaManager->getRepository('VitelogeCoreBundle:Ad');
            $ads = $repository->search($this->form->getData(), $limit);
            return $ads;
        }

        /**
         * @Route(
         *     "/search/{page}/{limit}",
         *     requirements={
         *         "page"="\d+",
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "page"=1,
         *         "limit"="25"
         *     }
         * )
         * @Route(
         *     "/search/",
         *     requirements={
         *         "page"="\d+",
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "page"=1,
         *         "limit"="25"
         *     }
         * )
         * Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:search.html.twig")
         */
        public function searchAction(Request $request, $page, $limit) {
            $adSearch = new AdSearch();
            $adSearch->handleRequest($request);
            $this->form = $this->createForm('viteloge_core_adsearch', $adSearch);
            $ads = $this->findFromRequest($request);
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate($ads, $page, $limit);
            return array(
                'form' => $this->form,
                'ads' => $pagination,
                'pagination' => $pagination
            );
        }

        /**
         * @Route(
         *     "/search/from/form/",
         *     name="viteloge_frontend_ad_searchfromform"
         * )
         * Cache(expires="tomorrow", public=true)
         * @Method({"POST"})
         */
        public function searchFromForm(Request $request) {
            $adSearch = new AdSearch();
            $form = $this->createForm('viteloge_core_adsearch', $adSearch);
            $form->handleRequest($request);

            // transform object to array in order to through it to url
            $encoders = array(new JsonEncoder());
            $normalizers = array(new GetSetMethodNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            $options = json_decode($serializer->serialize($form->getData(), 'json'), true);

            return $this->redirectToRoute(
                'viteloge_frontend_ad_search',
                $options,
                301
            );
        }

        /**
         * @Route(
         *     "/search/from/usersearch/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_ad_searchfromusersearch"
         * )
         * Cache(expires="tomorrow", public=true)
         * @ParamConverter("userSearch", class="VitelogeCoreBundle:UserSearch", options={"id" = "id"})
         * @Method({"GET"})
         */
        public function searchFromUserSearchAction(Request $request, UserSearch $userSearch) {
            $adSearch = new AdSearch();
            $adSearch->setTransaction($userSearch->getTransaction());
            $adSearch->setwhere($userSearch->getInseeCity()->getId());
            $adSearch->setwhat($userSearch->getType());
            $adSearch->setrooms($userSearch->getRooms());
            $adSearch->setminPrice($userSearch->getBudgetMin());
            $adSearch->setmaxPrice($userSearch->getBudgetMax());
            $adSearch->setradius($userSearch->getRadius());
            $adSearch->setkeywords($userSearch->getKeywords());

            // transform object to array in order to through it to url
            $encoders = array(new JsonEncoder());
            $normalizers = array(new GetSetMethodNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            $options = json_decode($serializer->serialize($adSearch, 'json'), true);

            return $this->redirectToRoute(
                'viteloge_frontend_ad_search',
                $options,
                301
            );
        }

        /**
         * @Route(
         *     "/carousel/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "9"
         *     },
         *     name="viteloge_frontend_ad_carousel"
         * )
         * @Route(
         *     "/carousel/",
         *     requirements={
         *         "limit" = "\d+"
         *     },
         *     defaults={
         *         "limit" = "9"
         *     },
         *     name="viteloge_frontend_ad_carousel"
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:carousel.html.twig")
         */
        public function carouselAction(Request $request, $limit) {
            $adSearch = new AdSearch();
            $adSearch->handleRequest($request);
            $elasticaManager = $this->container->get('fos_elastica.manager');
            $repository = $elasticaManager->getRepository('VitelogeCoreBundle:Ad');
            $transaction = (!empty($adSearch->getTransaction())) ? $adSearch->getTransaction() : 'default';
            $ads = $repository->search($adSearch, $limit);
            return array(
                'transaction' => $adSearch->getTransaction(),
                'ads' => $ads
            );
            /*
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:Ad');
            $cityRepository = $this->getDoctrine()
                ->getRepository('AcreatInseeBundle:InseeCity');

            $reader = new Reader('/usr/local/share/GeoIP/GeoLite2-City.mmdb', array('fr'));
            try {
                $ip = $this->getRequest()->getClientIp();
                $record = $reader->city($ip);
                $lat = $record->location->latitude;
                $lng = $record->location->longitude;
            } catch (\Exception $e) {
                // \GeoIp2\Exception\AddressNotFoundException
                $lat = 48.86;
                $lng = 2.35;
            }

            $criteria = array('transaction' => $transaction);
            $orderBy = array( 'privilegeRank' => 'DESC', 'order' => 'DESC', 'updatedAt' => 'DESC' );
            $city = $cityRepository->findOneByLatLng($lat, $lng);
            if ($city instanceof InseeCity) {
                $criteria['inseeCity'] = $city->getId();
            }
            $ads = $repository->findBy(
                $criteria,
                $orderBy,
                $limit
            );
            return array(
                'transaction' => $transaction,
                'ads' => $ads
            );*/
        }

        /**
         * @Route(
         *     "/latest/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "9"
         *     },
         *     name="viteloge_frontend_ad_latest"
         * )
         * @Route(
         *     "/latest/",
         *     defaults={
         *         "limit" = "9"
         *     },
         *     name="viteloge_frontend_ad_latest"
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:latest.html.twig")
         */
        public function latestAction(Request $request, $limit) {
            $adSearch = new AdSearch();
            $adSearch->handleRequest($request);
            $elasticaManager = $this->container->get('fos_elastica.manager');
            $repository = $elasticaManager->getRepository('VitelogeCoreBundle:Ad');
            $ads = $repository->search($adSearch, $limit);
            return array(
                'transaction' => $adSearch->getTransaction(),
                'cityName' => $request->query->get('cityName'),
                'ads' => $ads
            );
        }

        /**
         * @Route(
         *     "/suggest/new/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "3"
         *     }
         * )
         * @Route(
         *     "/suggest/new/",
         *     defaults={
         *         "limit" = "3"
         *     }
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:suggestNew.html.twig")
         */
        public function suggestNewAction(Request $request, $limit) {
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:Ad');
            $ads = $repository->findByAgencyIdNew(array('createdAt' => 'DESC'), $limit);
            return array(
                'ads' => $ads
            );
        }

        /**
         * @Route(
         *     "/redirect/{id}",
         *     requirements={
         *         "id"="\d+"
         *     }
         * )
         * @Method({"GET"})
         * @ParamConverter("ad", class="VitelogeCoreBundle:Ad", options={"id" = "id"})
         * @Template("VitelogeFrontendBundle:Ad:redirect.html.twig")
         */
        public function redirectAction(Request $request, Ad $ad) {
            return array(
                'ad' => $ad
            );
        }

    }

}
