<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;

    class WebSearchController extends Controller {

        /**
         * @Cache(expires="tomorrow", public=true)
         */
        public function latestAction(Request $request, $type=null, $limit=5) {
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:WebSearch');
            $websearches = $repository->findBy(
                array(),
                array( 'updatedAt' => 'DESC' ),
                $limit
            );
            return $this->render(
                'VitelogeFrontendBundle:WebSearch:latest.html.twig',
                array(
                    'websearches' => $websearches
                )
            );
        }

    }

}
