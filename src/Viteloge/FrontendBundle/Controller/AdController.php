<?php

namespace Viteloge\FrontendBundle\Controller {

    use Symfony\Component\HttpFoundation\Request;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

    /**
     * @Route("/ad")
     */
    class AdController extends Controller {

        /**
         * @Route("/latest/", requirements={"type" = "V|L|N", "limit" = "\d+"}, defaults={"type" = "V", "limit" = "9"})
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:carousel.html.twig")
         */
        public function latestAction(Request $request) {
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:Ad');
            $ads = $repository->findBy(
                array(),
                array( 'updatedAt' => 'DESC' ),
                $request->get('limit')
            );
            return array(
                'ads' => $ads
            );
        }

        /**
         * @Route("/list/", requirements={"type" = "V|L|N", "page" = "\d+", "limit" = "\d+"}, defaults={"type" = "V", "page" = 1, "limit" = "25"})
         * @Method({"GET", "POST"})
         * @Template("VitelogeFrontendBundle:Ad:list.html.twig")
         */
        public function listAction(Request $request) {
            $ads = array();
            $pagination = array();
            $repository = $this->getDoctrine()->getRepository('VitelogeCoreBundle:Ad');
            $ads = $repository->findBy(
                array(),
                array( 'updatedAt' => 'DESC' ),
                $request->get('limit')
            );

            return array(
                'ads' => $ads,
                'pagination' => $pagination
            );
        }

    }

}
