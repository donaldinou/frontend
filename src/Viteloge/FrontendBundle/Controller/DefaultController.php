<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Viteloge\CoreBundle\Component\DBAL\EnumTransactionType;
    use Viteloge\CoreBundle\Entity\Ad;
    use Viteloge\FrontendBundle\Form\Type\AdType;

    /**
     * @Route("/")
     */
    class DefaultController extends Controller {

        /**
         * @Route("/", requirements={"transaction" = "V|L|N"}, defaults={"transaction" = "L"}, name="viteloge_frontend_homepage")
         * @Method({"GET", "POST"})
         * @Template("VitelogeFrontendBundle:Default:index.html.twig")
         */
        public function indexAction( Request $request ) {
            $transactionEnum = EnumTransactionType::getValues();

            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:Ad');
            $count = $repository->countByFiltered();

            $ad = new Ad();
            $ad->setTransaction(array_search($request->get('transaction'), $transactionEnum));
            $form = $this->createForm('viteloge_frontend_ad', $ad);

            $form->handleRequest($request);
            if( $form->isValid() ) {
                $normalizer = new GetSetMethodNormalizer();
                $args = $normalizer->normalize($ad);
                if (isset($args['transaction'])) {
                    $args['transaction'] = $transactionEnum[$args['transaction']];
                }
                return $this->redirectToRoute('viteloge_frontend_ad_list', $args);
            }

            return array(
                'count' => $count,
                'form' => $form->createView()
            );
        }

    }

}
