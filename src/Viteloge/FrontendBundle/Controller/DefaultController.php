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
    use Viteloge\CoreBundle\Component\DBAL\EnumTransactionType;
    use Viteloge\CoreBundle\Entity\Ad;
    use Viteloge\CoreBundle\Entity\UserSearch;
    use Viteloge\FrontendBundle\Form\Type\AdType;

    /**
     * @Route("/")
     */
    class DefaultController extends Controller {

        /**
         * @Route(
         *     "/",
         *     requirements={
         *         "transaction"="V|L|N"
         *     },
         *     defaults={
         *         "transaction" = "L",
         *     },
         *     name="viteloge_frontend_homepage",
         *     options = {
         *         "i18n" = false
         *     }
         * )
         * @Method({"GET", "POST"})
         * @Template("VitelogeFrontendBundle:Default:index.html.twig")
         */
        public function indexAction( Request $request, $transaction ) {
            $transactionEnum = EnumTransactionType::getValues();

            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:Ad');
            $count = $repository->countByFiltered();

            $userSearch = new UserSearch();
            $userSearch->setTransaction(array_search($transaction, $transactionEnum));
            $form = $this->createForm('viteloge_frontend_usersearch', $userSearch);

            $form->handleRequest($request);
            if( $form->isValid() ) {
                $encoders = array(new JsonEncoder());
                $normalizers = array(new GetSetMethodNormalizer());
                $serializer = new Serializer($normalizers, $encoders);
                $args = json_decode($serializer->serialize($userSearch, 'json'), true);
                return $this->redirectToRoute('viteloge_frontend_ad_list', $args);
            }

            return array(
                'count' => $count,
                'form' => $form->createView()
            );
        }

    }

}
