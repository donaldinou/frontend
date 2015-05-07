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
    use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
    use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
    use Symfony\Component\Security\Acl\Permission\MaskBuilder;

    /**
     * @Route("/query")
     */
    class QueryStatsController extends Controller {

        /**
         * @Route(
         *     "/latest/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "9"
         *     }
         * )
         * @Route(
         *     "/latest/",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "9"
         *     }
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:QueryStats:latest.html.twig")
         */
        public function latestAction(Request $request, $limit, array $criteria=array()) {
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:QueryStats');
            if (isset($criteria['_hash'])) {
                unset($criteria['hash']);
            }
            $queries = $repository->findByFiltered(
                $criteria,
                array( 'timestamp' => 'DESC' ),
                $limit
            );
            return array(
                'queries' => $queries
            );
        }

    }

}
