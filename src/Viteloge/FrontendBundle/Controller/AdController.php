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

    /**
     * @Route("/search/ad")
     */
    class AdController extends Controller {

        /**
         * @Route(
         *     "/latest/{transaction}/{limit}",
         *     requirements={
         *         "transaction"="V|L|N",
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "transaction" = "V",
         *         "limit" = "9"
         *     }
         * )
         * @Route(
         *     "/latest/",
         *     requirements={
         *         "transaction"="V|L|N",
         *         "limit" = "\d+"
         *     },
         *     defaults={
         *         "transaction" = "V",
         *         "limit" = "9"
         *     }
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:carousel.html.twig")
         */
        public function latestAction(Request $request, $transaction, $limit) {
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

            $criteria = array();
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
                'ads' => $ads
            );
        }

        /**
         * @Route(
         *     "/list/{transaction}/{page}/{limit}",
         *     requirements={
         *         "transaction"="V|L|N",
         *         "page"="\d+",
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "transaction"="V",
         *         "page"=1,
         *         "limit"="25"
         *     }
         * )
         * @Route(
         *     "/list/",
         *     requirements={
         *         "transaction"="V|L|N",
         *         "page"="\d+",
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "transaction"="V",
         *         "page"=1,
         *         "limit"="25"
         *     }
         * )
         * @Method({"GET", "POST"})
         * @Template("VitelogeFrontendBundle:Ad:list.html.twig")
         */
        public function listAction(Request $request, $transaction, $page, $limit) {
            $repository = $this->getDoctrine()->getRepository('VitelogeCoreBundle:Ad');
            $criteria = array_merge(
                array('transaction' => $transaction),
                $request->query->all(),
                $request->request->all()
            );
            $orderBy = array_merge(
                array(
                    'privilegeRank' => 'DESC',
                    'order' => 'DESC'
                ),
                array()
            );
            $count = $repository->countByFiltered($criteria, $orderBy);

            $totalPages = ceil($count/$limit);
            $page = ($page<$totalPages) ? $page : $totalPages;
            $offset = ($page-1)*$limit;

            $ads = $repository->findByFiltered(
                $criteria,
                $orderBy,
                $limit,
                $offset
            );
            $pagination = array(
                'total' => $count,
                'total_pages' => ceil($count/$limit),
                'current' => $page,
                'route' => array(
                    'name' => 'viteloge_frontend_ad_list',
                    'parameters' => $criteria
                )
            );

            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem('Home', $this->get('router')->generate('viteloge_frontend_homepage'));
            $breadcrumbs->addItem('Result');

            return array(
                'ads' => $ads,
                'pagination' => $pagination,
                'criteria' => $criteria,
                'orderBy' => $orderBy
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
