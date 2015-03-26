<?php

namespace Viteloge\FrontendBundle\Controller {

    use Symfony\Component\HttpFoundation\Request;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Viteloge\CoreBundle\Entity\Ad;
    use Viteloge\FrontendBundle\Form\Type\AdType;

    /**
     * @Route("/")
     */
    class DefaultController extends Controller {

        /**
         * @Route("/", requirements={"type" = "V|L|N"}, defaults={"type" = "V"}, name="viteloge_frontend_homepage")
         * @Method({"GET", "POST"})
         * @Template("VitelogeFrontendBundle:Default:index.html.twig")
         */
        public function indexAction( Request $request ) {
            $ad = new Ad();
            $form = $this->createForm('viteloge_frontend_ad', $ad);

            $form->handleRequest($request);
            if( $form->isValid() ) {
                return $this->redirectToRoute('viteloge_frontend_ad_list', array());
            }

            return array(
                'type' => 'V',
                'form' => $form->createView()
            );
        }

    }

}
