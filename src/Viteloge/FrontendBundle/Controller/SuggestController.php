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

    /**
     * @Route("/suggest")
     */
    class SuggestController extends Controller {

        /**
         * @Route(
         *     "/cities/{_format}",
         *      requirements={
         *          "_format"="html|json"
         *      },
         *      defaults={
         *          "_format"="json"
         *      },
         *     name="viteloge_frontend_suggest_cities",
         *     options = {
         *         "i18n" = true
         *     }
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template()
         */
        public function citiesAction( Request $request, $_format ) {
            //if ($request->isXmlHttpRequest()) {
                //$serializer = $this->get('jms_serializer');
                //$inseeArea = $serializer->serialize($inseeArea, $_format);
            //}
            $options = array(
                'sort' => array(
                    'isCapital' => array( 'order' => 'desc' ),
                    'population' => array( 'order' => 'desc' )
                )
            );

            $search = $request->get('q', '');
            $index = $this->container->get('fos_elastica.finder.viteloge.inseeCity');
            $searchQuery = new \Elastica\Query\QueryString();
            $searchQuery->setParam('query', $search);
            $searchQuery->setDefaultOperator('AND');
            $searchQuery->setParam('fields', array(
                'name',
                'postalCode',
            ));

            $result = $index->find($searchQuery, $options);

            //$serializer = $this->get('jms_serializer');
            //$cities = $serializer->serialize($result, $_format);

            return array(
                'cities' => $result
            );
        }

        /**
         * @Route(
         *     "/states/{_format}",
         *      requirements={
         *          "_format"="html|json"
         *      },
         *      defaults={
         *          "_format"="json"
         *      },
         *     name="viteloge_frontend_suggest_states",
         *     options = {
         *         "i18n" = true
         *     }
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template()
         */
        public function statesAction( Request $request, $_format ) {
            $search = $request->get('q', '');

            $repository = $this->getDoctrine()
                ->getRepository('AcreatInseeBundle:InseeState');
            $result = $repository->findAll();

            return array(
                'states' => $result
            );
        }

        /**
         * @Route(
         *     "/departments/{_format}",
         *      requirements={
         *          "_format"="html|json"
         *      },
         *      defaults={
         *          "_format"="json"
         *      },
         *     name="viteloge_frontend_suggest_departments",
         *     options = {
         *         "i18n" = true
         *     }
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template()
         */
        public function departmentsAction( Request $request, $_format) {
            $search = $request->get('q', '');

            $repository = $this->getDoctrine()
                ->getRepository('AcreatInseeBundle:InseeDepartment');
            $result = $repository->findAll();

            return array(
                'departments' => $result
            );
        }

    }

}
