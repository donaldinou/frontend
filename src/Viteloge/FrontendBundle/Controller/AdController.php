<?php

namespace Viteloge\FrontendBundle\Controller {

    use Symfony\Component\HttpFoundation\Request;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use GeoIp2\Database\Reader;
    use Acreat\InseeBundle\Entity\InseeCity;

    /**
     * @Route("/search/ad")
     */
    class AdController extends Controller {

        /**
         * @Route("/latest/{transaction}/{limit}", requirements={"transaction" = "V|L|N", "limit" = "\d+"}, defaults={"transaction" = "V", "limit" = "9"})
         * @Route("/latest/", requirements={"transaction" = "V|L|N", "limit" = "\d+"}, defaults={"transaction" = "V", "limit" = "9"})
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:carousel.html.twig")
         */
        public function latestAction(Request $request) {
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

            $limit = $request->get('limit');
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
         * @Route("/list/{transaction}/{page}/{limit}", requirements={"transaction" = "V|L|N", "page" = "\d+", "limit" = "\d+"}, defaults={"transaction" = "V", "page" = 1, "limit" = "25"})
         * @Route("/list/", requirements={"transaction" = "V|L|N", "page" = "\d+", "limit" = "\d+"}, defaults={"transaction" = "V", "page" = 1, "limit" = "25"})
         * @Method({"GET", "POST"})
         * @Template("VitelogeFrontendBundle:Ad:list.html.twig")
         */
        public function listAction(Request $request) {
            $repository = $this->getDoctrine()->getRepository('VitelogeCoreBundle:Ad');
            $criteria = array_merge(
                array('transaction' => $request->get('transaction')),
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

            $limit = $request->get('limit');
            $totalPages = ceil($count/$limit);
            $page = ($request->get('page')<$totalPages) ? $request->get('page') : $totalPages;
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

            return array(
                'ads' => $ads,
                'pagination' => $pagination,
                'criteria' => $criteria,
                'orderBy' => $orderBy
            );
        }

        public function paginatedAction(Request $request) {
            return $this->listAction($request);
        }

    }

}
