<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;

    class GlossaryController extends Controller {

        /**
         * @Cache(expires="tomorrow", public=true)
         */
        public function mostSearchedAction(Request $request, $transaction=null, $limit=5) {
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:UserSearch');
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
