<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;

    class GlossaryController extends Controller {

        /**
         * @Cache(expires="tomorrow", public=true)
         */
        public function mostSearchedAction(Request $request, $type=null, $limit=5) {
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:Search');
            $glossary = $repository->findAllInseeCityOrderedByCount();
            return $this->render(
                'VitelogeFrontendBundle:Glossary:mostSearched.html.twig',
                array(
                    'glossary' => $glossary
                )
            );
        }

    }

}
